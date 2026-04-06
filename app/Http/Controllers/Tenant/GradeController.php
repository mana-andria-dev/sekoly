<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GradeController extends Controller
{
    public function index(Request $request)
    {
        $tenant = app('tenant');
        
        $grades = Grade::with(['student', 'subject', 'class', 'teacher'])
            ->where('tenant_id', $tenant->id)
            ->when($request->class_id, function($query, $classId) {
                return $query->where('class_id', $classId);
            })
            ->when($request->subject_id, function($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($request->period, function($query, $period) {
                return $query->where('period', $period);
            })
            ->orderBy('grade_date', 'desc')
            ->paginate(20);
        
        $classes = SchoolClass::where('tenant_id', $tenant->id)->get();
        $subjects = Subject::where('tenant_id', $tenant->id)->get();
        
        return view('tenant.grades.index', compact('grades', 'classes', 'subjects'));
    }
    
    public function create()
    {
        $tenant = app('tenant');
        $classes = SchoolClass::where('tenant_id', $tenant->id)->get();
        $subjects = Subject::where('tenant_id', $tenant->id)->get();
        
        return view('tenant.grades.create', compact('classes', 'subjects'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'grade_type' => 'required|in:homework,test,quiz,participation,project',
            'score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:1',
            'coefficient' => 'required|numeric|min:0.5|max:5',
            'grade_date' => 'required|date',
            'comment' => 'nullable|string',
            'period' => 'nullable|string',
        ]);
        
        $tenant = app('tenant');
        $validated['tenant_id'] = $tenant->id;
        
        // Récupérer l'ID du professeur
        $class = SchoolClass::find($validated['class_id']);
        $validated['teacher_id'] = $class->teacher_id;
        
        Grade::create($validated);
        
        return redirect()->route('grades.index', $tenant->name)
            ->with('success', 'Note ajoutée avec succès');
    }
    
    public function edit(Grade $grade)
    {
        $this->authorizeTenant($grade);
        
        $tenant = app('tenant');
        $classes = SchoolClass::where('tenant_id', $tenant->id)->get();
        $subjects = Subject::where('tenant_id', $tenant->id)->get();
        $students = User::whereHas('enrollments', function($query) use ($grade) {
            $query->where('class_id', $grade->class_id);
        })->where('role', 'student')->get();
        
        return view('tenant.grades.edit', compact('grade', 'classes', 'subjects', 'students'));
    }
    
    public function update(Request $request, Grade $grade)
    {
        $this->authorizeTenant($grade);
        
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'student_id' => 'required|exists:users,id',
            'title' => 'nullable|string|max:255',
            'grade_type' => 'required|in:homework,test,quiz,participation,project',
            'score' => 'required|numeric|min:0|max:100',
            'max_score' => 'required|numeric|min:1',
            'coefficient' => 'required|numeric|min:0.5|max:5',
            'grade_date' => 'required|date',
            'comment' => 'nullable|string',
            'period' => 'nullable|string',
        ]);
        
        $grade->update($validated);
        
        return redirect()->route('grades.index', app('tenant')->name)
            ->with('success', 'Note mise à jour avec succès');
    }
    
    public function destroy(Grade $grade)
    {
        $this->authorizeTenant($grade);
        $grade->delete();
        
        return redirect()->route('grades.index', app('tenant')->name)
            ->with('success', 'Note supprimée avec succès');
    }
    
    public function bulkCreate($classId, $subjectId)
    {
        $tenant = app('tenant');
        $class = SchoolClass::where('tenant_id', $tenant->id)->findOrFail($classId);
        $subject = Subject::where('tenant_id', $tenant->id)->findOrFail($subjectId);
        $students = $class->students()->where('role', 'student')->get();
        
        return view('tenant.grades.bulk', compact('class', 'subject', 'students'));
    }
    
    public function bulkStore(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.score' => 'required|numeric|min:0|max:100',
            'grades.*.max_score' => 'required|numeric|min:1',
            'grades.*.coefficient' => 'required|numeric|min:0.5|max:5',
            'grade_date' => 'required|date',
            'grade_type' => 'required|string',
            'period' => 'nullable|string',
        ]);
        
        $tenant = app('tenant');
        $class = SchoolClass::find($request->class_id);
        
        foreach ($request->grades as $gradeData) {
            Grade::create([
                'tenant_id' => $tenant->id,
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
                'teacher_id' => $class->teacher_id,
                'student_id' => $gradeData['student_id'],
                'score' => $gradeData['score'],
                'max_score' => $gradeData['max_score'],
                'coefficient' => $gradeData['coefficient'],
                'grade_date' => $request->grade_date,
                'grade_type' => $request->grade_type,
                'period' => $request->period,
                'title' => $request->title,
                'comment' => $gradeData['comment'] ?? null,
            ]);
        }
        
        return redirect()->route('grades.index', $tenant->name)
            ->with('success', 'Notes ajoutées en masse avec succès');
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}