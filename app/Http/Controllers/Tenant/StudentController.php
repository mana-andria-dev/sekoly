<?php

namespace App\Http\Controllers\Tenant;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StudentController extends Controller
{
    // Liste des élèves
    public function index(Request $request)
    {
        $search = $request->get('search');
        $classId = $request->get('class_id');
        $status = $request->get('status');

        // Obtenir l'année scolaire active
        $currentYear = SchoolYear::where('tenant_id', app('tenant')->id)
            ->where('is_active', true)
            ->first();
        
        // Construire la requête avec la dernière inscription active
        $students = User::students()
            ->forTenant()
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->when($classId, function($query) use ($classId, $currentYear) {
                $query->whereHas('studentEnrollments', function($q) use ($classId, $currentYear) {
                    $q->where('class_id', $classId);
                    if ($currentYear) {
                        $q->where('school_year_id', $currentYear->id);
                    }
                });
            })
            ->when($status, function($query) use ($status, $currentYear) {
                $query->whereHas('studentEnrollments', function($q) use ($status, $currentYear) {
                    $q->where('status', $status);
                    if ($currentYear) {
                        $q->where('school_year_id', $currentYear->id);
                    }
                });
            })
            ->with(['latestEnrollment' => function($query) use ($currentYear) {
                if ($currentYear) {
                    $query->where('school_year_id', $currentYear->id);
                }
                $query->with('schoolClass');
            }])
            ->orderBy('name')
            ->paginate(20);

        // Récupérer les classes pour le filtre
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->when($currentYear, function($query) use ($currentYear) {
                $query->where('school_year_id', $currentYear->id);
            })
            ->with('year')
            ->orderBy('name')
            ->get();

        // Statistiques
        $stats = [
            'total' => User::students()->forTenant()->count(),
            'active' => User::students()
                ->forTenant()
                ->whereHas('studentEnrollments', function($query) use ($currentYear) {
                    $query->where('status', 'active');
                    if ($currentYear) {
                        $query->where('school_year_id', $currentYear->id);
                    }
                })
                ->count(),
            'classes' => SchoolClass::forTenant()
                ->when($currentYear, function($query) use ($currentYear) {
                    $query->where('school_year_id', $currentYear->id);
                })
                ->whereHas('enrollments', function($query) use ($currentYear) {
                    $query->where('status', 'active');
                    if ($currentYear) {
                        $query->where('school_year_id', $currentYear->id);
                    }
                })->count(),
            'new_this_month' => User::students()
                ->forTenant()
                ->where('created_at', '>=', now()->startOfMonth())
                ->count(),
        ];

        return view('tenant.students.index', compact('students', 'classes', 'stats', 'currentYear'));
    }

    public function activate($tenant, User $student)
    {
        // $this->authorize('update', $student);
        
        $student->update(['is_active' => true]);
        
        return back()->with('success', 'Élève activé avec succès');
    }

    public function deactivate($tenant, User $student)
    {
        // $this->authorize('update', $student);
        
        $student->update(['is_active' => false]);
        
        return back()->with('success', 'Élève désactivé avec succès');
    }    

    // Formulaire création élève
    public function create()
    {
        // Récupérer toutes les années scolaires pour le select
        $schoolYears = SchoolYear::where('tenant_id', app('tenant')->id)
            ->orderBy('start_date', 'desc')
            ->get();
        
        // Année active par défaut
        $currentYear = SchoolYear::where('tenant_id', app('tenant')->id)
            ->where('is_active', true)
            ->first();
        
        // Récupérer toutes les classes
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->get(); 

        return view('tenant.students.create', compact('classes', 'currentYear', 'schoolYears'));
    }

    public function store( $tenant, Request $request)
    {
        // Valider les données avec messages personnalisés
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'required|date|before:today',
            'gender' => 'required|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:100',
            'emergency_relation' => 'nullable|string|max:50',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'school_year_id' => 'required|exists:school_years,id',
            'class_id' => 'required|exists:school_classes,id',
            'enrollment_date' => 'required|date',
            'roll_number' => 'nullable|string|max:50|unique:student_enrollments,roll_number',
            'section' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
            // Champs père
            'father_name' => 'nullable|string|max:100',
            'father_phone' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:100',
            'father_profession' => 'nullable|string|max:100',
            'father_cin' => 'nullable|string|max:20',
            
            // Champs mère
            'mother_name' => 'nullable|string|max:100',
            'mother_phone' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:100',
            'mother_profession' => 'nullable|string|max:100',
            'mother_cin' => 'nullable|string|max:20',
            
            // Champs tuteur
            'guardian_name' => 'nullable|string|max:100',
            'guardian_phone' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:100',
            'guardian_profession' => 'nullable|string|max:100',
            'guardian_cin' => 'nullable|string|max:20',
            'guardian_relation' => 'nullable|string|max:50',            
        ], [
            'first_name.required' => 'Le prénom est obligatoire.',
            'last_name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'date_of_birth.required' => 'La date de naissance est obligatoire.',
            'date_of_birth.before' => 'La date de naissance doit être antérieure à aujourd\'hui.',
            'gender.required' => 'Le genre est obligatoire.',
            'school_year_id.required' => 'L\'année scolaire est obligatoire.',
            'class_id.required' => 'La classe est obligatoire.',
            'enrollment_date.required' => 'La date d\'inscription est obligatoire.',
            'roll_number.unique' => 'Ce numéro de matricule est déjà utilisé.',
            'photo.image' => 'Le fichier doit être une image.',
            'photo.max' => 'L\'image ne doit pas dépasser 5MB.',
            'father_email.email' => 'L\'email du père doit être valide.',
            'mother_email.email' => 'L\'email de la mère doit être valide.',
            'guardian_email.email' => 'L\'email du tuteur doit être valide.',            
        ]);

        // Vérifier que la classe appartient bien à l'année scolaire sélectionnée
        $class = SchoolClass::find($validated['class_id']);
        if ($class->school_year_id != $validated['school_year_id']) {
            return back()
                ->withInput()
                ->withErrors([
                    'class_id' => 'La classe sélectionnée n\'appartient pas à l\'année scolaire choisie.'
                ]);
        }

        // Gérer l'upload de la photo
        $photoPath = null;
        if ($request->hasFile('photo')) {
            try {
                $photo = $request->file('photo');
                $filename = 'student_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('students', $filename, 'public');
            } catch (\Exception $e) {
                return back()
                    ->withInput()
                    ->withErrors(['photo' => 'Erreur lors du téléchargement de la photo: ' . $e->getMessage()]);
            }
        }

        try {
            // Générer un mot de passe aléatoire
            $tempPassword = Str::random(12);
            
            // Créer l'utilisateur élève
            $student = User::create([
                'tenant_id' => app('tenant')->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'date_of_birth' => $validated['date_of_birth'],
                'gender' => $validated['gender'],
                'address' => $validated['address'],
                'emergency_contact' => $validated['emergency_contact'],
                'emergency_relation' => $validated['emergency_relation'],
                'photo' => $photoPath,
                'password' => bcrypt($tempPassword),
                'role' => 'student',
                'is_active' => true,
                // Champs parents
                'father_name' => $validated['father_name'] ?? null,
                'father_phone' => $validated['father_phone'] ?? null,
                'father_email' => $validated['father_email'] ?? null,
                'father_profession' => $validated['father_profession'] ?? null,
                'father_cin' => $validated['father_cin'] ?? null,
                'mother_name' => $validated['mother_name'] ?? null,
                'mother_phone' => $validated['mother_phone'] ?? null,
                'mother_email' => $validated['mother_email'] ?? null,
                'mother_profession' => $validated['mother_profession'] ?? null,
                'mother_cin' => $validated['mother_cin'] ?? null,
                'guardian_name' => $validated['guardian_name'] ?? null,
                'guardian_phone' => $validated['guardian_phone'] ?? null,
                'guardian_email' => $validated['guardian_email'] ?? null,
                'guardian_profession' => $validated['guardian_profession'] ?? null,
                'guardian_cin' => $validated['guardian_cin'] ?? null,
                'guardian_relation' => $validated['guardian_relation'] ?? null,                
            ]);

            // Générer un numéro de matricule si non fourni
            $rollNumber = $validated['roll_number'] ?? $this->generateRollNumber($validated['class_id']);

            // Créer l'inscription
            StudentEnrollment::create([
                'tenant_id' => app('tenant')->id,
                'student_id' => $student->id,
                'class_id' => $validated['class_id'],
                'school_year_id' => $validated['school_year_id'],
                'enrollment_date' => $validated['enrollment_date'],
                'roll_number' => $rollNumber,
                'section' => $validated['section'],
                'remarks' => $validated['remarks'],
                'status' => 'active',
            ]);

            // Stocker le mot de passe temporaire en session
            $request->session()->flash('temp_password', $tempPassword);
            $request->session()->flash('student_email', $validated['email']);

            return redirect("/students")->with('success', 'Élève créé avec succès')
                ->with('show_password_alert', true);

        } catch (\Exception $e) {
            // En cas d'erreur, supprimer la photo uploadée si elle existe
            if ($photoPath && Storage::exists('public/' . $photoPath)) {
                Storage::delete('public/' . $photoPath);
            }

            return back()
                ->withInput()
                ->withErrors(['error' => 'Une erreur est survenue lors de la création: ' . $e->getMessage()]);
        }
    }

    // Méthode pour générer un numéro de matricule
    private function generateRollNumber($classId)
    {
        $class = SchoolClass::find($classId);
        $yearCode = date('y'); // Deux derniers chiffres de l'année
        $classCode = str_pad($class->id, 3, '0', STR_PAD_LEFT);
        
        // Compter les élèves déjà inscrits dans cette classe cette année
        $count = StudentEnrollment::where('class_id', $classId)
            ->whereYear('enrollment_date', date('Y'))
            ->count();
        
        $sequence = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
        
        return $yearCode . '-' . $classCode . '-' . $sequence;
    }

    // Affichage détail élève
    public function show($tenant, User $student)
    {
        // Vérifier que l'élève appartient au tenant
        if ($student->tenant_id !== app('tenant')->id) {
            abort(403, 'Accès non autorisé à cet élève.');
        }

        // Charger toutes les relations nécessaires
        $student->loadMissing(['studentEnrollments' => function($query) {
            $query->with(['schoolClass', 'schoolYear'])
                  ->orderBy('enrollment_date', 'desc');
        }]);

        // Récupérer l'inscription actuelle (la plus récente)
        $currentEnrollment = $student->studentEnrollments->first();
        
        // Toutes les inscriptions pour l'historique
        $enrollments = $student->studentEnrollments;

        // Compter les élèves dans la classe actuelle
        if ($currentEnrollment && $currentEnrollment->schoolClass) {
            $currentEnrollment->schoolClass->loadCount('students');
        }

        // return view('tenant.students.show', compact(
        //     'student',
        //     'currentEnrollment',
        //     'enrollments'
        // ));

        // Calculer l'âge
        $age = $student->date_of_birth ? $student->date_of_birth->age : null;

        // Calculer le temps dans le système
        $timeInSystem = $student->created_at->diffForHumans();

        // Récupérer les statistiques de la classe actuelle
        $classStats = null;
        if ($currentEnrollment && $currentEnrollment->schoolClass) {
            $classStats = [
                'total_students' => $currentEnrollment->schoolClass->students()->count(),
                'boys_count' => $currentEnrollment->schoolClass->students()
                    ->where('gender', 'male')->count(),
                'girls_count' => $currentEnrollment->schoolClass->students()
                    ->where('gender', 'female')->count(),
            ];
        }

        return view('tenant.students.show', compact(
            'student',
            'currentEnrollment',
            'enrollments',
            'age',
            'timeInSystem',
            'classStats'
        ));        
    }

    public function edit($tenant, User $student)
    {
        // $this->authorize('update', $student);
        
        $schoolYears = SchoolYear::where('tenant_id', app('tenant')->id)
            ->orderBy('start_date', 'desc')
            ->get();
        
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->orderBy('name')
            ->get();
            
        $currentEnrollment = $student->studentEnrollments()
            ->latest('enrollment_date')
            ->first();

        // Formater la date de naissance pour l'input HTML
        $student->date_of_birth_formatted = $student->date_of_birth 
            ? $student->date_of_birth->format('Y-m-d') 
            : old('date_of_birth', '');

        return view('tenant.students.edit', compact('student', 'currentEnrollment', 'schoolYears', 'classes'));
    }

    public function update($tenant, Request $request, User $student)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users')->ignore($student->id)
                ],
                'phone' => 'nullable|string|max:20',
                'date_of_birth' => 'required|date|before:today',
                'gender' => 'required|in:male,female,other',
                'address' => 'nullable|string',
                'emergency_contact' => 'nullable|string|max:100',
                'emergency_relation' => 'nullable|string|max:50',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
                'is_active' => 'boolean',
                'remove_photo' => 'nullable|boolean',
                
                // Ajoutez ces champs pour la classe
                'school_year_id' => 'nullable|exists:school_years,id',
                'class_id' => 'nullable|exists:school_classes,id',
                'section' => 'nullable|string|max:10',
                'roll_number' => 'nullable|string|max:50',
                'enrollment_date' => 'nullable|date',
                
                // Champs parents
                'father_name' => 'nullable|string|max:100',
                'father_phone' => 'nullable|string|max:20',
                'father_email' => 'nullable|email|max:100',
                'father_profession' => 'nullable|string|max:100',
                'father_cin' => 'nullable|string|max:20',
                'mother_name' => 'nullable|string|max:100',
                'mother_phone' => 'nullable|string|max:20',
                'mother_email' => 'nullable|email|max:100',
                'mother_profession' => 'nullable|string|max:100',
                'mother_cin' => 'nullable|string|max:20',
                'guardian_name' => 'nullable|string|max:100',
                'guardian_phone' => 'nullable|string|max:20',
                'guardian_email' => 'nullable|email|max:100',
                'guardian_profession' => 'nullable|string|max:100',
                'guardian_cin' => 'nullable|string|max:20',
                'guardian_relation' => 'nullable|string|max:50',                
            ], [
                // Messages existants...
                'class_id.exists' => 'La classe sélectionnée n\'existe pas.',
                'school_year_id.exists' => 'L\'année scolaire sélectionnée n\'existe pas.',
            ]);

            // Gérer la suppression de la photo
            if ($request->has('remove_photo') && $request->boolean('remove_photo')) {
                if ($student->photo && Storage::exists('public/' . $student->photo)) {
                    Storage::delete('public/' . $student->photo);
                }
                $validated['photo'] = null;
            }

            // Gérer l'upload d'une nouvelle photo
            if ($request->hasFile('photo')) {
                if ($student->photo && Storage::exists('public/' . $student->photo)) {
                    Storage::delete('public/' . $student->photo);
                }
                
                $photo = $request->file('photo');
                $filename = 'student_' . $student->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
                $photoPath = $photo->storeAs('students', $filename, 'public');
                $validated['photo'] = $photoPath;
            }

            // Mettre à jour les informations personnelles de l'élève
            $updateData = array_merge($validated, [
                'name' => $validated['first_name'] . ' ' . $validated['last_name']
            ]);

            // Supprimer les champs liés à l'inscription pour ne pas les mettre dans la table users
            unset($updateData['school_year_id']);
            unset($updateData['class_id']);
            unset($updateData['section']);
            unset($updateData['roll_number']);
            unset($updateData['enrollment_date']);
            unset($updateData['remove_photo']);

            // Mettre à jour l'élève
            $student->update($updateData);

            // Gérer la mise à jour de l'inscription scolaire si des champs sont fournis
            $successMessages = ['Informations personnelles mises à jour'];

            if ($request->filled('class_id') && $request->filled('school_year_id')) {
                $currentEnrollment = $student->studentEnrollments()
                    ->latest('enrollment_date')
                    ->first();

                if ($currentEnrollment) {
                    // Vérifier si la classe a changé
                    $classChanged = $currentEnrollment->class_id != $validated['class_id'];
                    $yearChanged = $currentEnrollment->school_year_id != $validated['school_year_id'];
                    
                    // Mettre à jour l'inscription existante
                    $currentEnrollment->update([
                        'class_id' => $validated['class_id'],
                        'school_year_id' => $validated['school_year_id'],
                        'section' => $validated['section'] ?? $currentEnrollment->section,
                        'roll_number' => $validated['roll_number'] ?? $currentEnrollment->roll_number,
                        'enrollment_date' => $validated['enrollment_date'] ?? $currentEnrollment->enrollment_date,
                    ]);

                    if ($classChanged) {
                        $newClass = SchoolClass::find($validated['class_id']);
                        $successMessages[] = "Classe modifiée: {$newClass->name}";
                    }
                    if ($yearChanged) {
                        $newYear = SchoolYear::find($validated['school_year_id']);
                        $successMessages[] = "Année scolaire modifiée: {$newYear->name}";
                    }
                } else {
                    // Créer une nouvelle inscription si aucune n'existe
                    StudentEnrollment::create([
                        'tenant_id' => app('tenant')->id,
                        'student_id' => $student->id,
                        'class_id' => $validated['class_id'],
                        'school_year_id' => $validated['school_year_id'],
                        'section' => $validated['section'] ?? null,
                        'roll_number' => $validated['roll_number'] ?? $this->generateRollNumber($validated['class_id']),
                        'enrollment_date' => $validated['enrollment_date'] ?? now(),
                        'status' => 'active',
                    ]);
                    $successMessages[] = "Nouvelle inscription créée";
                }
            }

            // Vérifier d'autres changements
            if ($request->hasFile('photo')) {
                $successMessages[] = 'Photo mise à jour';
            } elseif ($request->has('remove_photo')) {
                $successMessages[] = 'Photo supprimée';
            }

            if (isset($updateData['is_active']) && $student->wasChanged('is_active')) {
                $status = $student->is_active ? 'activé' : 'désactivé';
                $successMessages[] = "Statut: {$status}";
            }

            $message = implode(', ', $successMessages);

            return redirect()->route('students.index', ['tenant' => $tenant])
                ->with('success', $message);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Veuillez corriger les erreurs ci-dessous.');

        } catch (\Exception $e) {
        return back()
            ->withInput()
            ->with('error', 'Une erreur est survenue lors de la mise à jour: ' . $e->getMessage());
    }
}

    // Gestion de l'inscription
    public function updateEnrollment($tenant, Request $request, User $student)
    {
        // $this->authorize('update', $student);

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'status' => 'required|in:active,graduated,transferred,expelled,left',
            'roll_number' => 'nullable|string|max:50',
            'section' => 'nullable|string|max:10',
            'remarks' => 'nullable|string',
        ]);

        $currentEnrollment = $student->studentEnrollments()
            ->currentYear()
            ->first();

        if ($currentEnrollment) {
            $currentEnrollment->update($validated);
        }

        return back()->with('success', 'Inscription mise à jour');
    }

    // Nouvelle inscription (changement de classe)
    public function createEnrollment(Request $request, User $student)
    {
        // $this->authorize('update', $student);

        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'enrollment_date' => 'required|date',
            'roll_number' => 'nullable|string|max:50',
            'section' => 'nullable|string|max:10',
        ]);

        $class = SchoolClass::find($validated['class_id']);

        StudentEnrollment::create([
            'tenant_id' => app('tenant')->id,
            'student_id' => $student->id,
            'class_id' => $validated['class_id'],
            'school_year_id' => $class->school_year_id,
            'enrollment_date' => $validated['enrollment_date'],
            'roll_number' => $validated['roll_number'],
            'section' => $validated['section'],
            'status' => 'active',
        ]);

        return back()->with('success', 'Nouvelle inscription créée');
    }

    // Suppression élève
    public function destroy($tenant, User $student)
    {        
        $student->delete();
        
        return redirect('/students')
            ->with('success', 'Élève supprimé avec succès');
    }

    // Ajoutez ces méthodes
    public function importForm()
    {
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->orderBy('name')
            ->get();

        // Colonnes pour le mapping
        $requiredColumns = [
            'first_name' => 'Prénom',
            'last_name' => 'Nom',
            'email' => 'Email',
            'date_of_birth' => 'Date de naissance (YYYY-MM-DD)',
            'gender' => 'Genre (male/female/other)',
        ];

        $optionalColumns = [
            'phone' => 'Téléphone',
            'address' => 'Adresse',
            'emergency_contact' => 'Contact d\'urgence',
            'emergency_relation' => 'Relation d\'urgence',
            'class_code' => 'Code de la classe',
            'section' => 'Section (A, B, C...)',
            'roll_number' => 'Numéro de matricule',
            'remarks' => 'Remarques',
        ];

        $parentColumns = [
            'father_name' => 'Nom du père',
            'father_phone' => 'Téléphone du père',
            'father_email' => 'Email du père',
            'father_profession' => 'Profession du père',
            'father_cin' => 'CIN du père',
            'mother_name' => 'Nom de la mère',
            'mother_phone' => 'Téléphone de la mère',
            'mother_email' => 'Email de la mère',
            'mother_profession' => 'Profession de la mère',
            'mother_cin' => 'CIN de la mère',
            'guardian_name' => 'Nom du tuteur',
            'guardian_phone' => 'Téléphone du tuteur',
            'guardian_email' => 'Email du tuteur',
            'guardian_profession' => 'Profession du tuteur',
            'guardian_cin' => 'CIN du tuteur',
            'guardian_relation' => 'Relation du tuteur',
        ];

        return view('tenant.students.import', compact(
            'classes',
            'requiredColumns',
            'optionalColumns',
            'parentColumns'
        ));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240',
            'has_headers' => 'boolean',
            'skip_duplicates' => 'boolean',
            'generate_passwords' => 'boolean',
            'default_class_id' => 'nullable|exists:school_classes,id',
        ]);

        try {
            $file = $request->file('file');
            $hasHeaders = $request->boolean('has_headers', true);
            $skipDuplicates = $request->boolean('skip_duplicates', true);
            $generatePasswords = $request->boolean('generate_passwords', true);
            $defaultClassId = $request->input('default_class_id');
            
            // Get file extension
            $extension = $file->getClientOriginalExtension();
            
            // Process based on file type
            if (in_array($extension, ['xlsx', 'xls'])) {
                $data = $this->processExcelFile($file, $hasHeaders);
            } else {
                $data = $this->processCsvFile($file, $hasHeaders);
            }
            
            // Validate and process data
            $result = $this->processImportData($data, [
                'skip_duplicates' => $skipDuplicates,
                'generate_passwords' => $generatePasswords,
                'default_class_id' => $defaultClassId,
                'tenant_id' => app('tenant')->id,
            ]);
            
            // If AJAX request
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Importation terminée avec succès",
                    'total' => $result['total'],
                    'created' => $result['created'],
                    'updated' => $result['updated'],
                    'skipped' => $result['skipped'],
                    'errors' => $result['errors'],
                    'passwords' => $result['passwords'],
                    'download_url' => route('students.import.report', [
                        'tenant' => app('tenant')->name,
                        'reportId' => $result['report_id']
                    ]),
                ]);
            }
            
            return redirect('/students')
                ->with('success', "{$result['created']} élèves importés avec succès")
                ->with('info', "{$result['skipped']} lignes ignorées");
                
        } catch (\Exception $e) {
            \Log::error('Import error: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l\'importation: ' . $e->getMessage(),
                ], 422);
            }
            
            return back()
                ->with('error', 'Erreur lors de l\'importation: ' . $e->getMessage())
                ->withInput();
        }
    }

    private function processCsvFile($file, $hasHeaders = true)
    {
        $rows = [];
        $handle = fopen($file->getPathname(), 'r');
        
        if ($hasHeaders) {
            $headers = fgetcsv($handle, 1000, ',');
        }
        
        while (($row = fgetcsv($handle, 1000, ',')) !== false) {
            if ($hasHeaders) {
                $rowData = [];
                foreach ($headers as $index => $header) {
                    $rowData[trim($header)] = $row[$index] ?? null;
                }
                $rows[] = $rowData;
            } else {
                $rows[] = $row;
            }
        }
        
        fclose($handle);
        return $rows;
    }

    private function processExcelFile($file, $hasHeaders = true)
    {
        // Si vous avez installé maatwebsite/excel, utilisez-le:
        // $rows = Excel::toArray([], $file)[0];
        
        // Sinon, pour l'instant retourner un tableau vide
        // Vous pouvez implémenter la lecture Excel avec PhpSpreadsheet
        return [];
    }

    private function processImportData($data, $options = [])
    {
        $errors = [];
        $passwords = [];
        $created = 0;
        $skipped = 0;
        $updated = 0;
        
        foreach ($data as $index => $row) {
            try {
                // Skip empty rows
                if (empty(array_filter($row))) {
                    $skipped++;
                    continue;
                }
                
                // Validate required fields
                $validation = $this->validateImportRow($row);
                
                if (!$validation['valid']) {
                    $errors[] = [
                        'row' => $index + 2, // +2 because of header and 1-based index
                        'message' => $validation['message'],
                        'value' => $validation['value'] ?? null,
                    ];
                    $skipped++;
                    continue;
                }
                
                // Check for duplicate email
                $email = strtolower(trim($row['email']));
                $existingStudent = User::where('email', $email)
                    ->where('tenant_id', $options['tenant_id'])
                    ->first();
                
                if ($existingStudent && $options['skip_duplicates']) {
                    $skipped++;
                    continue;
                }
                
                // Prepare student data
                $studentData = [
                    'tenant_id' => $options['tenant_id'],
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'name' => $row['first_name'] . ' ' . $row['last_name'],
                    'email' => $email,
                    'phone' => $row['phone'] ?? null,
                    'date_of_birth' => $row['date_of_birth'],
                    'gender' => $row['gender'] ?? 'other',
                    'address' => $row['address'] ?? null,
                    'emergency_contact' => $row['emergency_contact'] ?? null,
                    'emergency_relation' => $row['emergency_relation'] ?? null,
                    'is_active' => true,
                    'role' => 'student',
                    
                    // Parent information
                    'father_name' => $row['father_name'] ?? null,
                    'father_phone' => $row['father_phone'] ?? null,
                    'father_email' => $row['father_email'] ?? null,
                    'father_profession' => $row['father_profession'] ?? null,
                    'father_cin' => $row['father_cin'] ?? null,
                    'mother_name' => $row['mother_name'] ?? null,
                    'mother_phone' => $row['mother_phone'] ?? null,
                    'mother_email' => $row['mother_email'] ?? null,
                    'mother_profession' => $row['mother_profession'] ?? null,
                    'mother_cin' => $row['mother_cin'] ?? null,
                    'guardian_name' => $row['guardian_name'] ?? null,
                    'guardian_phone' => $row['guardian_phone'] ?? null,
                    'guardian_email' => $row['guardian_email'] ?? null,
                    'guardian_profession' => $row['guardian_profession'] ?? null,
                    'guardian_cin' => $row['guardian_cin'] ?? null,
                    'guardian_relation' => $row['guardian_relation'] ?? null,
                ];
                
                // Generate password if needed
                if ($options['generate_passwords']) {
                    $password = Str::random(12);
                    $studentData['password'] = bcrypt($password);
                    $passwords[] = [
                        'email' => $email,
                        'password' => $password,
                    ];
                } else {
                    $studentData['password'] = bcrypt('password123'); // Default password
                }
                
                // Create or update student
                if ($existingStudent) {
                    $existingStudent->update($studentData);
                    $student = $existingStudent;
                    $updated++;
                } else {
                    $student = User::create($studentData);
                    $created++;
                }
                
                // Handle enrollment
                $this->processStudentEnrollment($student, $row, $options);
                
            } catch (\Exception $e) {
                $errors[] = [
                    'row' => $index + 2,
                    'message' => 'Erreur système: ' . $e->getMessage(),
                    'value' => null,
                ];
                $skipped++;
                \Log::error('Import row error: ' . $e->getMessage());
            }
        }
        
        return [
            'total' => count($data),
            'created' => $created,
            'updated' => $updated,
            'skipped' => $skipped,
            'errors' => $errors,
            'passwords' => $passwords,
            'report_id' => uniqid(),
        ];
    }

    private function validateImportRow($row)
    {
        // Check required fields
        $required = ['first_name', 'last_name', 'email', 'date_of_birth'];
        
        foreach ($required as $field) {
            if (empty($row[$field])) {
                return [
                    'valid' => false,
                    'message' => "Champ requis manquant: {$field}",
                    'value' => $row[$field] ?? null,
                ];
            }
        }
        
        // Validate email
        if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            return [
                'valid' => false,
                'message' => 'Email invalide',
                'value' => $row['email'],
            ];
        }
        
        // Validate date
        try {
            $date = \Carbon\Carbon::parse($row['date_of_birth']);
            if ($date->isFuture()) {
                return [
                    'valid' => false,
                    'message' => 'Date de naissance dans le futur',
                    'value' => $row['date_of_birth'],
                ];
            }
        } catch (\Exception $e) {
            return [
                'valid' => false,
                'message' => 'Format de date invalide (utilisez YYYY-MM-DD)',
                'value' => $row['date_of_birth'],
            ];
        }
        
        // Validate gender if provided
        if (isset($row['gender']) && !in_array(strtolower($row['gender']), ['male', 'female', 'other'])) {
            return [
                'valid' => false,
                'message' => 'Genre invalide (doit être male, female ou other)',
                'value' => $row['gender'],
            ];
        }
        
        return ['valid' => true];
    }

    private function processStudentEnrollment($student, $row, $options)
    {
        // Find class by code or use default
        $classId = null;
        
        if (!empty($row['class_code'])) {
            $class = SchoolClass::where('tenant_id', $options['tenant_id'])
                ->where('name', 'like', '%' . $row['class_code'] . '%')
                ->first();
            if ($class) {
                $classId = $class->id;
            }
        }
        
        if (!$classId && $options['default_class_id']) {
            $classId = $options['default_class_id'];
        }
        
        if ($classId) {
            // Get school year from class
            $class = SchoolClass::find($classId);
            
            // Create enrollment
            StudentEnrollment::create([
                'tenant_id' => $options['tenant_id'],
                'student_id' => $student->id,
                'class_id' => $classId,
                'school_year_id' => $class->school_year_id,
                'enrollment_date' => now(),
                'roll_number' => $row['roll_number'] ?? null,
                'section' => $row['section'] ?? null,
                'remarks' => $row['remarks'] ?? null,
                'status' => 'active',
            ]);
        }
    }

    public function downloadTemplate()
    {
        // Create CSV template
        $headers = [
            'first_name', 
            'last_name', 
            'email', 
            'date_of_birth', 
            'gender',
            'phone', 
            'address', 
            'emergency_contact', 
            'emergency_relation',
            'class_code', 
            'section', 
            'roll_number', 
            'remarks',
            'father_name', 
            'father_phone', 
            'father_email', 
            'father_profession', 
            'father_cin',
            'mother_name', 
            'mother_phone', 
            'mother_email', 
            'mother_profession', 
            'mother_cin',
            'guardian_name', 
            'guardian_phone', 
            'guardian_email', 
            'guardian_profession', 
            'guardian_cin', 
            'guardian_relation'
        ];
        
        $example = [
            'John', 
            'Doe', 
            'john.doe@example.com', 
            '2010-05-15', 
            'male',
            '+261 32 12 345 67', 
            '123 Rue Example', 
            'Jane Doe', 
            'Mère',
            '6A', 
            'A', 
            '2023-001', 
            'Élève brillant',
            'Robert Doe', 
            '+261 33 12 345 67', 
            'robert@example.com', 
            'Ingénieur', 
            '123456789012',
            'Jane Doe', 
            '+261 34 12 345 67', 
            'jane@example.com', 
            'Médecin', 
            '987654321098',
            'Marie Jeanne', 
            '+261343425262', 
            'mariie@example.com', 
            'Dev', 
            '123456712522', 
            'Tante'
        ];
        
        $filename = 'template-import-eleves-' . date('Y-m-d') . '.csv';
        
        // $handle = fopen('php://output', 'w');
        // fputcsv($handle, $headers);
        // fputcsv($handle, $example);
        // fclose($handle);
        
        return response()->streamDownload(function() use ($headers, $example) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);
            fputcsv($handle, $example);
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function downloadReport($reportId)
    {
        // Generate and download import report
        // Implementation depends on how you want to store reports
        
        $filename = 'rapport-import-' . $reportId . '.csv';
        
        return response()->streamDownload(function() {
            // Generate report content
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }    

}