<?php

namespace App\Http\Controllers\Tenant;

use App\Models\ReportCard;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use App\Models\User;
use App\Services\GradeCalculationService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportCardController extends Controller
{
    protected $gradeService;
    
    public function __construct(GradeCalculationService $gradeService)
    {
        $this->gradeService = $gradeService;
    }
    
    public function index(Request $request)
    {
        
        $reportCards = ReportCard::with(['student', 'class', 'schoolYear'])
            ->when($request->class_id, function($query, $classId) {
                return $query->where('class_id', $classId);
            })
            ->when($request->period, function($query, $period) {
                return $query->where('period', $period);
            })
            ->when($request->status, function($query, $status) {
                return $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        $classes = SchoolClass::get();
        $schoolYears = SchoolYear::get();
        
        return view('tenant.report-cards.index', compact('reportCards', 'classes', 'schoolYears'));
    }
    
    public function create()
    {
        $classes = SchoolClass::get();
        $schoolYears = SchoolYear::get();
        
        $periods = [
            'trimester1' => '1er Trimestre',
            'trimester2' => '2ème Trimestre',
            'trimester3' => '3ème Trimestre',
            'semester1' => '1er Semestre',
            'semester2' => '2ème Semestre',
            'annual' => 'Annuel',
        ];
        
        return view('tenant.report-cards.create', compact('classes', 'schoolYears', 'periods'));
    }
    
    public function generate(Request $request)
    {
        $request->validate([
            'class_id' => 'required|exists:school_classes,id',
            'school_year_id' => 'required|exists:school_years,id',
            'period' => 'required|string',
        ]);
        
        $class = SchoolClass::find($request->class_id);
        $schoolYear = SchoolYear::find($request->school_year_id);
        
        // Récupérer les étudiants de cette classe UNIQUEMENT
        $students = $class->students()->where('role', 'student')->get();
        
        \Log::info('Génération des bulletins', [
            'class_id' => $class->id,
            'class_name' => $class->name,
            'students_count' => $students->count(),
            'students' => $students->map(function($s) {
                return ['id' => $s->id, 'name' => $s->first_name . ' ' . $s->last_name];
            })->toArray()
        ]);
        
        $generatedCount = 0;
        
        foreach ($students as $student) {
            // Vérifier si le bulletin existe déjà
            $existingReport = ReportCard::where('student_id', $student->id)
                ->where('school_year_id', $schoolYear->id)
                ->where('period', $request->period)
                ->first();
            
            $reportData = $this->gradeService->generateReportCardData($student, $class, $schoolYear, $request->period);
            
            \Log::info('Données du bulletin', [
                'student' => $student->first_name . ' ' . $student->last_name,
                'subject_grades_count' => count($reportData['subject_grades']),
                'overall_average' => $reportData['overall_average']
            ]);
            
            ReportCard::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'school_year_id' => $schoolYear->id,
                    'period' => $request->period,
                ],
                [
                    'class_id' => $class->id,
                    'subject_grades' => $reportData['subject_grades'],
                    'overall_average' => $reportData['overall_average'],
                    'class_rank' => $reportData['class_rank'],
                    'total_students' => $reportData['total_students'],
                    'issued_date' => now(),
                    'status' => 'draft',
                ]
            );
            
            $generatedCount++;
        }
        
        return redirect()->route('report-cards.index')
            ->with('success', $generatedCount . ' bulletin(s) généré(s) avec succès');
    }
    
    public function show($id)
    {
        $reportCard = ReportCard::findOrFail($id);
        $reportCard->load(['student', 'class', 'schoolYear']);
        
        return view('tenant.report-cards.show', compact('reportCard'));
    }
    
    public function edit($id)
    {
        $reportCard = ReportCard::findOrFail($id);
        
        return view('tenant.report-cards.edit', compact('reportCard'));
    }
    
    public function update(Request $request, $id)
    {
        $reportCard = ReportCard::findOrFail($id);
        
        $validated = $request->validate([
            'appreciation' => 'nullable|string',
            'teacher_comments' => 'nullable|string',
            'principal_comments' => 'nullable|string',
            'absences' => 'nullable|array',
            'behaviors' => 'nullable|array',
            'status' => 'required|in:draft,published,archived',
        ]);
        
        $reportCard->update($validated);
        
        return redirect()->route('report-cards.show', $reportCard->id)
            ->with('success', 'Bulletin mis à jour avec succès');
    }
    
    public function publish($id)
    {
        $reportCard = ReportCard::findOrFail($id);
        $reportCard->update(['status' => 'published']);
        
        return redirect()->back()->with('success', 'Bulletin publié avec succès');
    }
    
    public function print($id)
    {
        $reportCard = ReportCard::findOrFail($id);
        $reportCard->load(['student', 'class', 'schoolYear']);
        
        return view('tenant.report-cards.print', compact('reportCard'));
    }
    
    public function classReportCards(SchoolClass $class, $period = null)
    {   
        $query = ReportCard::where('class_id', $class->id)
            ->with(['student', 'schoolYear']);
        
        if ($period) {
            $query->where('period', $period);
        }
        
        $reportCards = $query->get();
        
        return view('tenant.report-cards.class', compact('reportCards', 'class', 'period'));
    }
    
    private function authorizeTenant($model)
    {
        if ($model->tenant_id !== app('tenant')->id) {
            abort(403);
        }
    }
}