<?php
// app/Http/Controllers/Tenant/ExamController.php

namespace App\Http\Controllers\Tenant;

use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExamController extends Controller
{
    public function index(Request $request)
    {   
        $exams = Exam::with(['class', 'subject', 'teacher'])
            ->when($request->class_id, function($query, $classId) {
                return $query->where('class_id', $classId);
            })
            ->when($request->subject_id, function($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($request->type, function($query, $type) {
                return $query->where('type', $type);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('exam_date', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('exam_date', '<=', $date);
            })
            ->orderBy('exam_date', 'desc')
            ->paginate(15);
            
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        
        return view('tenant.exams.index', compact('exams', 'classes', 'subjects'));
    }
    
    public function create()
    {
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.exams.create', compact('classes', 'subjects', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:trimester,semester,final,quiz,test',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration_minutes' => 'required|integer|min:15|max:360',
            'max_score' => 'required|numeric|min:1|max:100',
            'coefficient' => 'required|numeric|min:0.5|max:5',
            'location' => 'nullable|string|max:255',
            'topics' => 'nullable|array',
            'instructions' => 'nullable|array',
        ]);
        
        $validated['status'] = 'scheduled';
        
        // Calculer la durée en minutes si non fournie
        if (empty($validated['duration_minutes'])) {
            $start = \Carbon\Carbon::parse($validated['start_time']);
            $end = \Carbon\Carbon::parse($validated['end_time']);
            $validated['duration_minutes'] = $start->diffInMinutes($end);
        }
        
        Exam::create($validated);
        
        return redirect()->route('exams.index')
            ->with('success', 'Examen créé avec succès');
    }
    
    public function show($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->load(['class', 'subject', 'teacher', 'results.student']);
        
        // Récupérer les élèves de la classe depuis la table users
        $students = User::whereHas('enrollments', function($query) use ($exam) {
            $query->where('class_id', $exam->class_id)
                  ->where('status', 'active');
        })->where('role', 'student')
          ->get();
        
        // Statistiques
        $totalStudents = $students->count();
        $resultsCount = $exam->results->count();
        $gradedCount = $exam->results->where('score', '!=', null)->count();
        $averageScore = $exam->results->where('score', '!=', null)->avg('score');
        
        return view('tenant.exams.show', compact('exam', 'students', 'totalStudents', 'resultsCount', 'gradedCount', 'averageScore'));
    }
    
    public function edit($id)
    {
        $exam = Exam::findOrFail($id);
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.exams.edit', compact('exam', 'classes', 'subjects', 'teachers'));
    }
    
    public function update(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:trimester,semester,final,quiz,test',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration_minutes' => 'required|integer|min:15|max:360',
            'max_score' => 'required|numeric|min:1|max:100',
            'coefficient' => 'required|numeric|min:0.5|max:5',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'topics' => 'nullable|array',
            'instructions' => 'nullable|array',
        ]);
        
        $exam->update($validated);
        
        return redirect()->route('exams.index')
            ->with('success', 'Examen mis à jour avec succès');
    }
    
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);
        
        // Supprimer également les résultats
        $exam->results()->delete();
        $exam->delete();
        
        return redirect()->route('exams.index', app('tenant')->name)
            ->with('success', 'Examen supprimé avec succès');
    }
    
    public function storeResults(Request $request, $id)
    {
        $exam = Exam::findOrFail($id);
        
        // Récupérer les IDs des élèves de la classe (depuis users)
        $studentIds = $exam->class->students()->pluck('users.id')->toArray();
        \Log::info('Élèves de la classe (users):', $studentIds);
        
        // Validation
        $request->validate([
            'results' => 'required|array',
            'results.*.student_id' => 'required|integer|in:' . implode(',', $studentIds),
            'results.*.score' => 'required|numeric|min:0|max:' . $exam->max_score,
            'results.*.feedback' => 'nullable|string',
        ]);
        
        $savedCount = 0;
        
        foreach ($request->results as $result) {
            try {
                ExamResult::updateOrCreate(
                    [
                        'exam_id' => $exam->id,
                        'student_id' => $result['student_id'],
                    ],
                    [
                        'score' => floatval($result['score']),
                        'feedback' => $result['feedback'] ?? null,
                        'recorded_at' => now(),
                        'recorded_by' => auth()->id(),
                    ]
                );
                $savedCount++;
            } catch (\Exception $e) {
                \Log::error('Erreur:', ['message' => $e->getMessage()]);
            }
        }
        
        return redirect()->back()->with('success', $savedCount . ' résultat(s) enregistré(s)');
    }    
    
    public function results($id)
    {
        $exam = Exam::findOrFail($id);
        $exam->load(['results.student']);
        
        return view('tenant.exams.results', compact('exam'));
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}