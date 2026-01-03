<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use App\Models\TeacherEvaluation;
use Illuminate\Http\Request;

class TeacherEvaluationController extends Controller
{
    public function index(Teacher $teacher)
    {
        $evaluations = $teacher->evaluations()->with('evaluator')->orderBy('evaluation_date', 'desc')->paginate(10);
        return view('tenant.teachers.evaluations.index', compact('teacher', 'evaluations'));
    }

    public function create($tenant, Teacher $teacher)
    {
        return view('tenant.teachers.evaluations.create', compact('teacher'));
    }

    public function store($tenant, Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'evaluation_type' => 'required|in:annual,probation,performance,student_feedback',
            'evaluation_date' => 'required|date',
            'pedagogical_skills' => 'required|numeric|min:0|max:10',
            'subject_knowledge' => 'required|numeric|min:0|max:10',
            'classroom_management' => 'required|numeric|min:0|max:10',
            'communication' => 'required|numeric|min:0|max:10',
            'punctuality' => 'required|numeric|min:0|max:10',
            'strengths' => 'required|string',
            'improvements_needed' => 'nullable|string',
            'recommendations' => 'nullable|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
        ]);

        // Calcul de la note globale
        $scores = [
            $validated['pedagogical_skills'],
            $validated['subject_knowledge'],
            $validated['classroom_management'],
            $validated['communication'],
            $validated['punctuality']
        ];
        $validated['overall_rating'] = array_sum($scores) / count($scores);

        $evaluation = new TeacherEvaluation($validated);
        $evaluation->teacher_id = $teacher->id;
        $evaluation->evaluator_id = auth()->id();

        // Upload du document
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('evaluations', 'public');
            $evaluation->document_path = $path;
        }

        $evaluation->save();

        return redirect()->route('teachers.show', [
            'tenant' => app('tenant')->name,
            'teacher' => $teacher->id
        ])->with('success', 'Évaluation enregistrée avec succès.');
    }
}