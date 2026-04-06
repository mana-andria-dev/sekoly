<?php

namespace App\Services;

use App\Models\User;
use App\Models\Subject;
use App\Models\Grade;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\SchoolClass;
use App\Models\SchoolYear;
use Illuminate\Support\Collection;

class GradeCalculationService
{

    public function calculateSubjectAverage(User $student, Subject $subject, $period = null, $schoolYearId = null, $classId = null)
    {
        $totalWeightedScore = 0;
        $totalWeight = 0;
        
        // NE PAS filtrer par période pour le moment
        $examsQuery = Exam::where('subject_id', $subject->id)
            ->where('class_id', $classId);
        
        // Filtrer par période seulement si elle est fournie ET si des examens existent avec cette période
        if ($period) {
            // Vérifier d'abord s'il y a des examens avec cette période
            $examsWithPeriod = Exam::where('subject_id', $subject->id)
                ->where('class_id', $classId)
                ->where('period', $period)
                ->exists();
            
            if (!$examsWithPeriod) {
                // Si aucun examen avec la période spécifiée, prendre tous les examens de la matière
                \Log::warning('Aucun examen trouvé pour la période ' . $period . ', prise de tous les examens', [
                    'subject' => $subject->name,
                    'class_id' => $classId
                ]);
                // Ne pas filtrer par période
            } else {
                $examsQuery->where('period', $period);
            }
        }
        
        $exams = $examsQuery->get();
        
        \Log::info('Examens trouvés (sans filtre période strict)', [
            'subject' => $subject->name,
            'count' => $exams->count(),
            'exams' => $exams->map(function($e) {
                return [
                    'id' => $e->id,
                    'title' => $e->title,
                    'period' => $e->period,
                    'status' => $e->status
                ];
            })->toArray()
        ]);
        
        foreach ($exams as $exam) {
            $examResult = ExamResult::where('exam_id', $exam->id)
                ->where('student_id', $student->id)
                ->first();
            
            if ($examResult && $examResult->score !== null) {
                $scoreOver20 = ($examResult->score / $exam->max_score) * 20;
                $totalWeightedScore += $scoreOver20 * $exam->coefficient;
                $totalWeight += $exam->coefficient;
            }
        }
        
        return $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : null;
    }
    
    /**
     * Calculer la moyenne de la classe pour une matière
         */
    public function calculateClassAverageForSubject(SchoolClass $class, Subject $subject, $period = null)
    {
        $students = $class->students()->where('role', 'student')->get();
        $averages = [];
        
        foreach ($students as $student) {
            $average = $this->calculateSubjectAverage($student, $subject, $period, null, $class->id);
            if ($average !== null) {
                $averages[] = $average;
            }
        }
        
        $classAverage = count($averages) > 0 ? round(array_sum($averages) / count($averages), 2) : null;
        
        \Log::info('Moyenne de classe pour matière', [
            'subject' => $subject->name,
            'class_average' => $classAverage,
            'students_count' => count($averages)
        ]);
        
        return $classAverage;
    }
    
    /**
     * Calculer le rang de l'élève dans sa classe
     */
    public function calculateClassRank(User $student, SchoolClass $class, $period = null)
    {
        $students = $class->students()->where('role', 'student')->get();
        
        $assignments = $class->assignments()
            ->with('subject')
            ->where('is_active', true)
            ->get();
        
        if ($assignments->isEmpty()) {
            return null;
        }
        
        $averages = [];
        foreach ($students as $s) {
            $totalWeightedScore = 0;
            $totalWeight = 0;
            
            foreach ($assignments as $assignment) {
                $subject = $assignment->subject;
                if (!$subject) continue;
                
                $average = $this->calculateSubjectAverage($s, $subject, $period, null, $class->id);
                if ($average !== null) {
                    $coefficient = (float)($assignment->coefficient ?? 1);
                    $totalWeightedScore += $average * $coefficient;
                    $totalWeight += $coefficient;
                }
            }
            
            $averages[$s->id] = $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : 0;
        }
        
        arsort($averages);
        $rank = array_search($student->id, array_keys($averages));
        
        return $rank !== false ? $rank + 1 : null;
    }
    
    /**
     * Générer les données pour un bulletin
     */
    public function generateReportCardData(User $student, SchoolClass $class, SchoolYear $schoolYear, $period)
    {
        \Log::info('Génération bulletin pour élève', [
            'student' => $student->first_name . ' ' . $student->last_name,
            'class_id' => $class->id,
            'period' => $period
        ]);
        
        // Récupérer toutes les matières assignées à la classe
        $assignments = $class->assignments()
            ->with('subject')
            ->where('is_active', true)
            ->get();
        
        \Log::info('Matières assignées', [
            'count' => $assignments->count(),
            'subjects' => $assignments->map(function($a) {
                return $a->subject ? $a->subject->name : null;
            })->toArray()
        ]);
        
        if ($assignments->isEmpty()) {
            return [
                'subject_grades' => [],
                'overall_average' => null,
                'class_rank' => null,
                'total_students' => $class->students()->where('role', 'student')->count(),
            ];
        }
        
        $subjectGrades = [];
        
        foreach ($assignments as $assignment) {
            $subject = $assignment->subject;
            if (!$subject) continue;
            
            // Calculer la moyenne pour cette matière
            $average = $this->calculateSubjectAverage($student, $subject, $period, $schoolYear->id, $class->id);
            $classAverage = $this->calculateClassAverageForSubject($class, $subject, $period);
            
            $subjectGrades[] = [
                'subject_id' => $subject->id,
                'subject_name' => $subject->name,
                'subject_code' => $subject->code ?? '',
                'coefficient' => (float)($assignment->coefficient ?? 1),
                'average' => $average,
                'class_average' => $classAverage,
                'appreciation' => $this->getSubjectAppreciation($average),
            ];
        }
        
        // Calculer la moyenne générale
        $overallAverage = $this->calculateOverallAverageFromGrades($subjectGrades);
        
        // Calculer le rang
        $classRank = $this->calculateClassRank($student, $class, $period);
        $totalStudents = $class->students()->where('role', 'student')->count();
        
        \Log::info('Résultat final bulletin', [
            'student' => $student->first_name . ' ' . $student->last_name,
            'subject_grades' => $subjectGrades,
            'overall_average' => $overallAverage,
            'class_rank' => $classRank
        ]);
        
        return [
            'subject_grades' => $subjectGrades,
            'overall_average' => $overallAverage,
            'class_rank' => $classRank,
            'total_students' => $totalStudents,
        ];
    }
    
    /**
     * Calculer la moyenne générale à partir des notes par matière
     */
    private function calculateOverallAverageFromGrades($subjectGrades)
    {
        if (empty($subjectGrades)) {
            return null;
        }
        
        $totalWeightedScore = 0;
        $totalWeight = 0;
        
        foreach ($subjectGrades as $grade) {
            if (isset($grade['average']) && $grade['average'] !== null) {
                $totalWeightedScore += $grade['average'] * $grade['coefficient'];
                $totalWeight += $grade['coefficient'];
            }
        }
        
        return $totalWeight > 0 ? round($totalWeightedScore / $totalWeight, 2) : null;
    }
    
    /**
     * Obtenir l'appréciation pour une matière
     */
    private function getSubjectAppreciation($average)
    {
        if ($average === null) return 'Non évalué';
        
        if ($average >= 16) return 'Excellent';
        if ($average >= 14) return 'Très bien';
        if ($average >= 12) return 'Bien';
        if ($average >= 10) return 'Satisfaisant';
        return 'À améliorer';
    }
}