<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Homework;
use App\Models\HomeworkSubmission;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;

class HomeworkController extends Controller
{
    public function index(Request $request)
    {
        $homeworks = Homework::with(['class', 'subject', 'teacher'])
            ->when($request->class_id, function($query, $classId) {
                return $query->where('class_id', $classId);
            })
            ->when($request->subject_id, function($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('due_date', 'desc')
            ->paginate(15);
            
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        
        return view('tenant.homeworks.index', compact('homeworks', 'classes', 'subjects'));
    }
    
    public function create()
    {
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.homeworks.create', compact('classes', 'subjects', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|integer|min:1|max:100',
            'type' => 'required|in:homework,project,research',
            'instructions' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);
        
        $validated['status'] = 'active';
        $tenant = tenant();
        
        // Gérer les fichiers joints
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('homeworks/' . $tenant->id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
            $validated['attachments'] = json_encode($attachments);
        }
        
        Homework::create($validated);
        
        return redirect()->route('homeworks.index')
            ->with('success', 'Devoir créé avec succès');
    }
    
    public function show($id)
    {
        $homework = Homework::findOrFail($id);
        $homework->load(['class', 'subject', 'teacher', 'submissions.student']);
        $classId = $homework->class_id;
        
        $students = User::students()
            ->when($classId, function($query) use ($classId) {
                $query->whereHas('studentEnrollments', function($q) use ($classId) {
                    $q->where('class_id', $classId);
                });
            })
            ->get();

        return view('tenant.homeworks.show', compact('homework', 'students'));
    }
    
    public function edit($id)
    {
        $homework = Homework::findOrFail($id);
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.homeworks.edit', compact('homework', 'classes', 'subjects', 'teachers'));
    }
    
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'max_score' => 'required|integer|min:1|max:100',
            'type' => 'required|in:homework,project,research',
            'instructions' => 'nullable|string',
            'status' => 'required|in:active,expired,cancelled',
        ]);
        
        $homework = Homework::findOrFail($id);
        $homework->update($validated);
        
        return redirect()->route('homeworks.index')
            ->with('success', 'Devoir mis à jour avec succès');
    }
    
    public function destroy($id)
    {
        $homework = Homework::findOrFail($id);
        
        // Supprimer les fichiers joints
        if ($homework->attachments) {
            foreach (json_decode($homework->attachments, true) as $attachment) {
                Storage::disk('public')->delete($attachment['path']);
            }
        }
        
        $homework->delete();
        
        return redirect()->route('homeworks.index')
            ->with('success', 'Devoir supprimé avec succès');
    }
    
    public function submit(Request $request, Homework $homework)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'submission_text' => 'nullable|string',
            'attachments' => 'nullable|array',
        ]);
        
        $submission = HomeworkSubmission::updateOrCreate(
            [
                'homework_id' => $homework->id,
                'student_id' => $request->student_id,
            ],
            [
                'submission_text' => $request->submission_text,
                'submitted_at' => now(),
                'status' => 'submitted',
            ]
        );
        
        // Gérer les fichiers joints
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('submissions/' . $homework->tenant_id, 'public');
                $attachments[] = [
                    'name' => $file->getClientOriginalName(),
                    'path' => $path,
                    'size' => $file->getSize(),
                ];
            }
            $submission->update(['attachments' => json_encode($attachments)]);
        }
        
        return redirect()->back()->with('success', 'Devoir soumis avec succès');
    }
    
    public function grade(Request $request, HomeworkSubmission $submission)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:' . $submission->homework->max_score,
            'feedback' => 'nullable|string',
        ]);
        
        $submission->update([
            'score' => $request->score,
            'feedback' => $request->feedback,
            'graded_at' => now(),
            'status' => 'graded',
        ]);
        
        return redirect()->back()->with('success', 'Devoir noté avec succès');
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}