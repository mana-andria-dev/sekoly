<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Lesson;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    public function index(Request $request)
    {
        // $tenant = app('tenant');
        
        $lessons = Lesson::with(['class', 'subject', 'teacher'])
            ->when($request->class_id, function($query, $classId) {
                return $query->where('class_id', $classId);
            })
            ->when($request->subject_id, function($query, $subjectId) {
                return $query->where('subject_id', $subjectId);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->when($request->date_from, function($query, $date) {
                return $query->whereDate('lesson_date', '>=', $date);
            })
            ->when($request->date_to, function($query, $date) {
                return $query->whereDate('lesson_date', '<=', $date);
            })
            ->orderBy('lesson_date', 'desc')
            ->paginate(15);
            
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        
        return view('tenant.lessons.index', compact('lessons', 'classes', 'subjects'));
    }
    
    public function create()
    {
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.lessons.create', compact('classes', 'subjects', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'lesson_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:regular,revision,practical',
            'resources' => 'nullable|array',
            'objectives' => 'nullable|array',
        ]);
        
        $validated['status'] = 'scheduled';
        
        Lesson::create($validated);
        
        return redirect()->route('lessons.index')
            ->with('success', 'Leçon créée avec succès');
    }
    
    public function show($id)
    {
        // $this->authorizeTenant($lesson);
        $lesson = Lesson::findOrFail($id);
        $lesson->load(['class', 'subject', 'teacher']);
        
        return view('tenant.lessons.show', compact('lesson'));
    }
    
    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        $classes = SchoolClass::get();
        $subjects = Subject::get();
        $teachers = Teacher::where('status', 'active')->get();
            
        return view('tenant.lessons.edit', compact('lesson', 'classes', 'subjects', 'teachers'));
    }
    
    public function update(Request $request, $id)
    {   
        $validated = $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'lesson_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'type' => 'required|in:regular,revision,practical',
            'status' => 'required|in:scheduled,ongoing,completed,cancelled',
            'resources' => 'nullable|array',
            'objectives' => 'nullable|array',
        ]);
        
        $lesson = Lesson::findOrFail($id);
        $lesson->update($validated);
        
        return redirect()->route('lessons.index')
            ->with('success', 'Leçon mise à jour avec succès');
    }
    
    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        $lesson->delete();
        
        return redirect()->route('lessons.index')
            ->with('success', 'Leçon supprimée avec succès');
    }
    
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:scheduled,ongoing,completed,cancelled'
        ]);
        
        $lesson = Lesson::findOrFail($id);
        $lesson->update(['status' => $request->status]);
        
        return response()->json(['success' => true]);
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}