<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\User;
use App\Models\SchoolClass;
use App\Models\ClassAssignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class TeacherController extends Controller
{

    public function index(Request $request)
    {
        $query = Teacher::with(['subjects', 'currentContract'])
                       ->withCount(['assignments', 'classes']);

        // Filtres
        if ($request->has('search')) {
            $query->search($request->search);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('subject_id')) {
            $query->whereHas('subjects', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        $teachers = $query->paginate(20);
        $subjects = Subject::all();
        $statuses = ['active', 'inactive', 'on_leave', 'retired'];

        return view('tenant.teachers.index', compact('teachers', 'subjects', 'statuses'));
    }

    public function create()
    {
        $subjects = Subject::all();
        return view('tenant.teachers.create', compact('subjects'));
    }

    public function store(Request $request)
    {
        try {
            \Log::info('=== DÉBUT CRÉATION PROFESSEUR ===');
            \Log::info('Données reçues:', $request->all());
            
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|in:M,F',
                'date_of_birth' => 'required|date',
                'email' => 'required|email|unique:teachers',
                'phone' => 'nullable|string|max:20',
                'hire_date' => 'required|date',
                'employment_type' => 'required|in:CDI,CDD,Vacataire,Contractuel',
                'academic_degree' => 'required|string|max:100',
                'specialization' => 'required|string|max:100',
                'id_number' => 'nullable|string|max:50',
                'social_security_number' => 'nullable|string|max:50',
                'nationality' => 'nullable|string|max:50',
                'address' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:100',
                'country' => 'nullable|string|max:100',
                'hourly_rate' => 'nullable|numeric|min:0',
                'hours_per_week' => 'nullable|integer|min:1|max:60',
                'bank_name' => 'nullable|string|max:100',
                'bank_account' => 'nullable|string|max:50',
                'emergency_contact_name' => 'nullable|string|max:100',
                'emergency_contact_phone' => 'nullable|string|max:20',
                'emergency_contact_relation' => 'nullable|string|max:50',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'notes' => 'nullable|string',
            ]);

            \Log::info('Validation réussie');

            // Gérer l'upload de la photo
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('teachers/photos', 'public');
                $validated['photo'] = $path;
                \Log::info('Photo uploadée:', ['path' => $path]);
            }

            // **CRITIQUE : Utiliser l'ID comme base pour teacher_id**
            // On va d'abord créer, puis générer teacher_id basé sur l'ID
            
            // Créer le professeur d'abord (sans teacher_id)
            $teacher = Teacher::create($validated);
            \Log::info('Professeur créé avec ID:', ['id' => $teacher->id]);

            // **MÉTHODE SIMPLE : Utiliser l'ID auto-incrémenté comme numéro**
            // C'est garanti d'être unique
            $nextNumber = $teacher->id;
            $newTeacherId = 'PROF-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            
            \Log::info('Génération teacher_id basé sur ID:', [
                'id' => $teacher->id,
                'teacher_id_proposé' => $newTeacherId
            ]);

            // **VÉRIFICATION D'URGENCE**
            // Vérifier si ce teacher_id existe déjà (au cas où)
            $counter = 0;
            $maxAttempts = 100;
            
            while (Teacher::where('teacher_id', $newTeacherId)->where('id', '!=', $teacher->id)->exists()) {
                \Log::warning('Teacher ID existe déjà, incrémentation:', ['teacher_id' => $newTeacherId]);
                $nextNumber++;
                $newTeacherId = 'PROF-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                $counter++;
                
                if ($counter >= $maxAttempts) {
                    throw new \Exception("Impossible de générer un teacher_id unique après $maxAttempts tentatives");
                }
            }
            
            // Mettre à jour avec le nouveau teacher_id
            $teacher->teacher_id = $newTeacherId;
            $teacher->save();
            
            \Log::info('Teacher ID final attribué:', [
                'id' => $teacher->id,
                'teacher_id' => $teacher->teacher_id
            ]);

            // Attacher les matières
            if ($request->has('subjects')) {
                foreach ($request->input('subjects', []) as $subjectId => $subjectData) {
                    if (isset($subjectData['selected']) && $subjectData['selected'] == '1') {
                        $teacher->subjects()->attach($subjectId, [
                            'experience_years' => $subjectData['experience_years'] ?? 0,
                            'proficiency_level' => $subjectData['proficiency_level'] ?? 'intermediate',
                            'is_primary' => isset($subjectData['is_primary']) && $subjectData['is_primary'] == '1',
                        ]);
                    }
                }
            }

            // Créer un utilisateur associé si demandé
            try {
                
                // Vérifier si un utilisateur avec cet email existe déjà
                $existingUser = User::where('email', $teacher->email)
                    ->first();
                
                if ($existingUser) {
                    // Utiliser l'utilisateur existant
                    $teacher->user_id = $existingUser->id;
                    $teacher->save();
                    
                    // Mettre à jour le rôle si nécessaire
                    if ($existingUser->role !== 'teacher') {
                        $existingUser->role = 'teacher';
                        $existingUser->save();
                    }
                    
                    \Log::info('Utilisateur existant associé:', ['user_id' => $existingUser->id]);
                } else {
                    // Créer un nouvel utilisateur
                    $userData = [
                        'first_name' => $teacher->first_name,
                        'last_name' => $teacher->last_name,
                        'name' => $teacher->full_name,
                        'email' => $teacher->email,
                        'password' => Hash::make(Str::random(12)),
                        'role' => 'teacher',
                        'is_active' => true,
                        'date_of_birth' => $teacher->date_of_birth,
                        'gender' => $teacher->gender,
                        'phone' => $teacher->phone,
                        'address' => $teacher->address,
                    ];
                    
                    // Ajouter les champs conditionnels
                    if (Schema::hasColumn('users', 'photo')) {
                        $userData['photo'] = $teacher->photo;
                    }
                    
                    $user = User::create($userData);
                    $teacher->user_id = $user->id;
                    $teacher->save();
                    
                    \Log::info('Nouvel utilisateur créé:', ['user_id' => $user->id]);
                }
                \Log::info('=== CRÉATION RÉUSSIE ===');
                return redirect()->route('teachers.show', [
                    'tenant' => app('tenant')->name,
                    'teacher' => $teacher->id
                ])->with('success', 'Professeur créé avec succès.');
                
            } catch (\Exception $userException) {
                \Log::error('Erreur création/association compte utilisateur: ' . $userException->getMessage());
                // NE PAS échouer complètement, on peut continuer sans compte utilisateur
                // Mais loguer l'erreur pour débogage
                // session()->flash('warning', 'Professeur créé, mais compte utilisateur non créé: ' . $userException->getMessage());
                return redirect()->route('teachers.show', [
                    // 'tenant' => app('tenant')->name,
                    'teacher' => $teacher->id
                ])->with('warning', 'Professeur créé, mais compte utilisateur non créé: ' . $userException->getMessage());                
            }


        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Erreur validation:', $e->errors());
            return redirect()->back()
                ->withInput()
                ->withErrors($e->errors());
                
        } catch (\Exception $e) {
            \Log::error('Erreur création professeur:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->load([
            'teacherSubjects',
            'availabilities',
            'contracts' => function ($query) {
                $query->orderBy('start_date', 'desc');
            },
            'evaluations' => function ($query) {
                $query->orderBy('evaluation_date', 'desc');
            },
            'assignments' => function ($query) {
                $query->with(['subject', 'schoolClass'])
                      ->active()
                      ->current()
                      ->orderBy('start_date', 'desc');
            }
        ]);

        $weeklyWorkload = $teacher->getWeeklyWorkload();
        $subjects = Subject::forTenant()->get();
        
        // Récupérer les classes et matières disponibles
        $assignedClassIds = $teacher->assignments->pluck('class_id')->toArray();
        $availableClasses = SchoolClass::active()
            ->whereNotIn('id', $assignedClassIds)
            ->get();
        
        // Utiliser les matières que le professeur peut enseigner
        $teacherSubjectIds = $teacher->teacherSubjects->pluck('id')->toArray();
        
        // Si le professeur a des matières assignées, montrer seulement celles-ci
        if (!empty($teacherSubjectIds)) {
            $availableSubjects = Subject::forTenant()
                ->whereIn('id', $teacherSubjectIds)
                ->get();
        } else {
            // Sinon, montrer toutes les matières
            $availableSubjects = Subject::forTenant()->get();
        }

        return view('tenant.teachers.show', compact(
            'teacher', 
            'weeklyWorkload', 
            'subjects',
            'availableClasses',
            'availableSubjects'
        ));
    }

    public function edit($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->load('subjects');
        $subjects = Subject::all();
        
        return view('tenant.teachers.edit', compact('teacher', 'subjects'));
    }

    public function update(Request $request, $id)
    {
        try {
            $teacher = Teacher::findOrFail($id);
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'gender' => 'required|in:M,F',
                'date_of_birth' => 'required|date',
                'email' => 'required|email|unique:teachers,email,' . $teacher->id,
                'phone' => 'nullable|string|max:20',
                'hire_date' => 'required|date',
                'employment_type' => 'required|in:CDI,CDD,Vacataire,Contractuel',
                'academic_degree' => 'required|string|max:100',
                'specialization' => 'required|string|max:100',
                'status' => 'required|in:active,inactive,on_leave,retired',
                // ... autres règles
            ]);

            // Gérer l'upload de la photo
            if ($request->hasFile('photo')) {
                // Supprimer l'ancienne photo si elle existe
                if ($teacher->photo) {
                    Storage::disk('public')->delete($teacher->photo);
                }
                
                $path = $request->file('photo')->store('teachers/photos', 'public');
                $validated['photo'] = $path;
            }

            $teacher->update($validated);

            // Mettre à jour les matières
            if ($request->has('subjects')) {
                $teacher->subjects()->detach();
                foreach ($request->subjects as $subjectId => $subjectData) {
                    if (isset($subjectData['id'])) {
                        $teacher->subjects()->attach($subjectData['id'], [
                            'experience_years' => $subjectData['experience_years'] ?? 0,
                            'proficiency_level' => $subjectData['proficiency_level'] ?? 'intermediate',
                            'is_primary' => $subjectData['is_primary'] ?? false,
                        ]);
                    }
                }
            }

            return back()->with('success', 'Professeur mis à jour.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue: ' . $e->getMessage()])
                ->with('error', 'Erreur lors de la mise à jour du professeur.');
        }
    }

    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        // Vérifier si le professeur a des affectations actives
        if ($teacher->assignments()->active()->exists()) {
            return back()->with('error', 'Impossible de supprimer un professeur avec des affectations actives.');
        }

        $teacher->delete();
        
        return redirect()->route('teachers.index')
                       ->with('success', 'Professeur archivé.');
    }

    // Méthodes supplémentaires
    public function schedule($tenant, Teacher $teacher)
    {
        // Récupérer l'emploi du temps du professeur
        $schedule = $teacher->assignments()
                           ->with(['class', 'subject'])
                           ->get()
                           ->groupBy('day_of_week');

        return view('tenant.teachers.schedule', compact('teacher', 'schedule'));
    }

    public function workloadReport($tenant, Teacher $teacher)
    {
        $workload = $teacher->assignments()
                           ->selectRaw('subject_id, SUM(hours_per_week) as total_hours')
                           ->with('subject')
                           ->groupBy('subject_id')
                           ->get();

        return view('tenant.teachers.reports.workload', compact('teacher', 'workload'));
    }
   
}