<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\ClassAssignment;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AssignmentController extends Controller
{
    /**
     * Display a listing of assignments with filters
     */
    public function index(Request $request)
    {
        // Récupérer les classes avec scope forTenant
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->when($request->has('active'), function($query) use ($request) {
                // Vous pouvez ajouter une logique pour "active" si nécessaire
                return $query;
            })
            ->get();

        $subjects = Subject::forTenant()
            ->when($request->has('active'), function($query) use ($request) {
                return $query->where('is_active', $request->boolean('active'));
            })
            ->get();

        $teachers = User::where('tenant_id', app('tenant')->id)
            ->where('role', 'teacher')
            ->when($request->has('active'), function($query) use ($request) {
                return $query->where('is_active', $request->boolean('active'));
            })
            ->get();

        // La requête pour les affectations
        $query = ClassAssignment::where('tenant_id', app('tenant')->id)
            ->with(['schoolClass.year', 'subject', 'teacher'])
            ->when($request->class_id, function($q, $classId) {
                return $q->where('class_id', $classId);
            })
            ->when($request->subject_id, function($q, $subjectId) {
                return $q->where('subject_id', $subjectId);
            })
            ->when($request->teacher_id, function($q, $teacherId) {
                return $q->where('teacher_id', $teacherId);
            })
            ->when($request->has('active'), function($q) use ($request) {
                return $q->where('is_active', $request->boolean('active'));
            });

        if ($request->sort_by) {
            $query->orderBy($request->sort_by, $request->sort_order ?? 'asc');
        } else {
            $query->orderBy('class_id')->orderBy('subject_id');
        }

        $assignments = $query->paginate(30)->withQueryString();

        return view('tenant.assignments.index', compact(
            'assignments', 
            'classes', 
            'subjects', 
            'teachers'
        ));
    }

    public function create(Request $request)
    {
        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->orderBy('name')
            ->get();

        $subjects = Subject::forTenant()
            ->active()
            ->orderBy('name')
            ->get();

        $teachers = User::where('tenant_id', app('tenant')->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        $prefilled = [
            'class_id' => $request->class_id,
            'subject_id' => $request->subject_id,
            'teacher_id' => $request->teacher_id,
        ];

        return view('tenant.assignments.create', compact(
            'classes', 
            'subjects', 
            'teachers',
            'prefilled'
        ));
    }

    public function edit($tenant, ClassAssignment $assignment)
    {
        // $this->authorize('update', $assignment);

        $classes = SchoolClass::where('tenant_id', app('tenant')->id)
            ->with('year')
            ->orderBy('name')
            ->get();

        $subjects = Subject::forTenant()
            ->active()
            ->orderBy('name')
            ->get();

        $teachers = User::where('tenant_id', app('tenant')->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('tenant.assignments.edit', compact(
            'assignment',
            'classes',
            'subjects',
            'teachers'
        ));
    }

    /**
     * Store a newly created assignment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => [
                'required',
                'exists:school_classes,id',
                Rule::unique('class_assignments', 'class_id')
                    ->where('subject_id', $request->subject_id)
                    ->where('tenant_id', app('tenant')->id)
            ],
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'hours_per_week' => 'required|integer|min:0|max:40',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'is_active' => 'boolean',
        ], [
            'class_id.unique' => 'Cette matière est déjà assignée à cette classe.',
        ]);

        $assignment = ClassAssignment::create([
            'tenant_id' => app('tenant')->id,
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/assignments')->with('success', 'Affectation créée avec succès.');
    }

    /**
     * Show assignment details
     */
    public function show($tenant, ClassAssignment $assignment)
    {
        // $this->authorize('view', $assignment);
        
        $assignment->load(['schoolClass.year', 'subject', 'teacher']);
        
        return view('tenant.assignments.show', compact('assignment'));
    }

    /**
     * Update an assignment
     */
    public function update($tenant, Request $request, ClassAssignment $assignment)
    {
        // $this->authorize('update', $assignment);

        $validated = $request->validate([
            'class_id' => [
                'required',
                'exists:school_classes,id',
                Rule::unique('class_assignments', 'class_id')
                    ->where('subject_id', $request->subject_id)
                    ->where('tenant_id', app('tenant')->id)
                    ->ignore($assignment->id)
            ],
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'hours_per_week' => 'required|integer|min:0|max:40',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'is_active' => 'boolean',
        ], [
            'class_id.unique' => 'Cette matière est déjà assignée à cette classe.',
        ]);

        $assignment->update([
            ...$validated,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect('/assignments')->with('success', 'Affectation mise à jour avec succès.');
    }

    /**
     * Delete an assignment
     */
    public function destroy($tenant, ClassAssignment $assignment)
    {
        // $this->authorize('delete', $assignment);

        $assignment->delete();

        return redirect('/assignments')->with('success', 'Affectation supprimée avec succès.');
    }

    /**
     * Bulk assignments from class view
     */
    public function bulkCreate($tenant, SchoolClass $schoolClass)
    {
        // $this->authorize('create', ClassAssignment::class);

        // Vérifier que la classe appartient au tenant
        if ($schoolClass->tenant_id !== app('tenant')->id) {
            abort(403, 'Cette classe ne vous appartient pas.');
        }
        
        $assignedSubjects = $schoolClass->assignments()->pluck('subject_id')->toArray();
        
        $subjects = Subject::forTenant()
            ->active()
            ->whereNotIn('id', $assignedSubjects)
            ->orderBy('name')
            ->get();

        $teachers = User::where('tenant_id', app('tenant')->id)
            ->where('role', 'teacher')
            ->where('is_active', true)
            ->orderBy('first_name')
            ->get();

        return view('tenant.assignments.bulk-create', compact(
            'schoolClass',
            'subjects',
            'teachers'
        ));
    }

    /**
     * Store bulk assignments
     */
    public function bulkStore($tenant, Request $request, SchoolClass $schoolClass)
    {
        // $this->authorize('create', ClassAssignment::class);

        $validated = $request->validate([
            'subjects' => 'required|array|min:1',
            'subjects.*.subject_id' => 'required|exists:subjects,id',
            'subjects.*.teacher_id' => 'nullable|exists:users,id',
            'subjects.*.hours_per_week' => 'required|integer|min:0|max:40',
            'subjects.*.coefficient' => 'required|numeric|min:0.1|max:10',
        ]);

        $created = 0;
        $errors = [];

        foreach ($validated['subjects'] as $subjectData) {
            // Check if assignment already exists
            $exists = ClassAssignment::forTenant()
                ->where('class_id', $schoolClass->id)
                ->where('subject_id', $subjectData['subject_id'])
                ->exists();

            if (!$exists) {
                ClassAssignment::create([
                    'tenant_id' => app('tenant')->id,
                    'class_id' => $schoolClass->id,
                    ...$subjectData,
                    'is_active' => true,
                ]);
                $created++;
            } else {
                $errors[] = "La matière {$subjectData['subject_id']} est déjà assignée.";
            }
        }

        $message = $created . ' affectation(s) créée(s) avec succès.';
        if (!empty($errors)) {
            $message .= ' ' . implode(' ', $errors);
        }

        return redirect()
            ->route('classes.show', $schoolClass)
            ->with($errors ? 'warning' : 'success', $message);
    }

    /**
     * Toggle active status
     */
    public function toggleActive($tenant, ClassAssignment $assignment)
    {
        // $this->authorize('update', $assignment);

        $assignment->update([
            'is_active' => !$assignment->is_active
        ]);

        $status = $assignment->is_active ? 'activée' : 'désactivée';
        
        return back()->with('success', "Affectation {$status} avec succès.");
    }

    /**
     * Get assignments by class (for API)
     */
    public function byClass($tenant, SchoolClass $schoolClass)
    {
        $assignments = ClassAssignment::forTenant()
            ->forClass($schoolClass->id)
            ->active()
            ->with(['subject', 'teacher'])
            ->get();

        return response()->json($assignments);
    }

    /**
     * Get assignments by teacher (for API)
     */
    public function byTeacher($tenant, User $teacher)
    {
        $assignments = ClassAssignment::forTenant()
            ->forTeacher($teacher->id)
            ->active()
            ->with(['schoolClass.year', 'subject'])
            ->get();

        return response()->json($assignments);
    }
}