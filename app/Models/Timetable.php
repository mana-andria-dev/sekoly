<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timetable extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'class_id',
        'academic_year_id',
        'start_date',
        'end_date',
        'is_active',
        'created_by',
        'type'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function academicYear()
    {
        return $this->belongsTo(SchoolYear::class, 'academic_year_id');
    }

    public function timetableSlots()
    {
        return $this->hasMany(TimetableSlot::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // ========== SCOPES ==========
    
    /**
     * Scope pour filtrer par tenant
     */
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    /**
     * Scope pour les emplois du temps actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les emplois du temps d'une classe spécifique
     */
    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    /**
     * Scope pour les emplois du temps d'une année académique
     */
    public function scopeForAcademicYear($query, $academicYearId)
    {
        return $query->where('academic_year_id', $academicYearId);
    }

    /**
     * Scope pour les emplois du temps créés par un utilisateur
     */
    public function scopeCreatedBy($query, $userId)
    {
        return $query->where('created_by', $userId);
    }

    /**
     * Scope pour les emplois du temps avec créneaux
     */
    public function scopeWithSlots($query)
    {
        return $query->has('timetableSlots');
    }

    /**
     * Scope pour les emplois du temps sans créneaux
     */
    public function scopeWithoutSlots($query)
    {
        return $query->doesntHave('timetableSlots');
    }

    // ========== BUSINESS LOGIC METHODS ==========
    
    /**
     * Vérifier les conflits d'horaires dans l'emploi du temps
     */
    public function checkConflicts()
    {
        $conflicts = collect(); // Retourner une collection
        
        // Récupérer tous les créneaux groupés par jour
        $slots = $this->timetableSlots()
            ->with(['subject', 'teacher', 'teacherProfile', 'classroom'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();
            
        $groupedSlots = $slots->groupBy('day_of_week');
        
        // Vérifier les conflits par jour
        foreach ($groupedSlots as $day => $daySlots) {
            $daySlots = $daySlots->sortBy('start_time');
            
            // Vérifier les chevauchements temporels
            for ($i = 0; $i < count($daySlots); $i++) {
                for ($j = $i + 1; $j < count($daySlots); $j++) {
                    $slot1 = $daySlots[$i];
                    $slot2 = $daySlots[$j];
                    
                    // Si les créneaux se chevauchent
                    if ($this->slotsOverlap($slot1, $slot2)) {
                        // Vérifier les différents types de conflits
                        $conflictTypes = $this->detectConflictTypes($slot1, $slot2);
                        
                        foreach ($conflictTypes as $type) {
                            $conflicts->push((object)[
                                'day_of_week' => $day,
                                'start_time' => $slot1->start_time,
                                'end_time' => $slot1->end_time,
                                'slot1' => $slot1,
                                'slot2' => $slot2,
                                'type' => $type,
                                'conflict_details' => $this->getConflictDetails($slot1, $slot2, $type)
                            ]);
                        }
                    }
                }
            }
        }
        
        return $conflicts->unique();
    }

    /**
     * Vérifier si deux créneaux se chevauchent
     */
    private function slotsOverlap($slot1, $slot2)
    {
        $start1 = strtotime($slot1->start_time);
        $end1 = strtotime($slot1->end_time);
        $start2 = strtotime($slot2->start_time);
        $end2 = strtotime($slot2->end_time);
        
        return ($start1 < $end2 && $end1 > $start2);
    }

    /**
     * Détecter les types de conflits entre deux créneaux
     */
    private function detectConflictTypes($slot1, $slot2)
    {
        $types = [];
        
        // Conflit de professeur
        if ($slot1->teacher_id && $slot2->teacher_id && 
            $slot1->teacher_id == $slot2->teacher_id) {
            $types[] = 'teacher_conflict';
        }
        
        // Conflit de professeur profile
        if ($slot1->teacher_profile_id && $slot2->teacher_profile_id && 
            $slot1->teacher_profile_id == $slot2->teacher_profile_id) {
            $types[] = 'teacher_profile_conflict';
        }
        
        // Conflit de salle de classe
        if ($slot1->classroom_id && $slot2->classroom_id && 
            $slot1->classroom_id == $slot2->classroom_id) {
            $types[] = 'classroom_conflict';
        }
        
        // Si pas de conflit spécifique, marquer comme chevauchement général
        if (empty($types)) {
            $types[] = 'time_overlap';
        }
        
        return $types;
    }

    /**
     * Obtenir les détails du conflit
     */
    private function getConflictDetails($slot1, $slot2, $type)
    {
        switch ($type) {
            case 'teacher_conflict':
                return "Le professeur {$slot1->teacher->name} a deux cours en même temps";
                
            case 'teacher_profile_conflict':
                $teacherName = $slot1->teacherProfile ? 
                    $slot1->teacherProfile->first_name . ' ' . $slot1->teacherProfile->last_name : 
                    'Professeur inconnu';
                return "Le professeur $teacherName a deux cours en même temps";
                
            case 'classroom_conflict':
                $classroom1 = $slot1->classroom ? $slot1->classroom->code : 'Salle inconnue';
                $classroom2 = $slot2->classroom ? $slot2->classroom->code : 'Salle inconnue';
                return "La salle $classroom1 est utilisée pour deux cours en même temps";
                
            case 'time_overlap':
            default:
                $subject1 = $slot1->subject ? $slot1->subject->name : 'Matière inconnue';
                $subject2 = $slot2->subject ? $slot2->subject->name : 'Matière inconnue';
                return "Deux créneaux se chevauchent: $subject1 et $subject2";
        }
    }

    /**
     * Générer automatiquement l'emploi du temps à partir des affectations
     */
    public function generateFromAssignments()
    {
        // Récupérer les affectations de la classe
        $assignments = ClassAssignment::where('tenant_id', $this->tenant_id)
            ->where('class_id', $this->class_id)
            ->where('is_active', true)
            ->get();

        // Nettoyer les anciens créneaux
        $this->timetableSlots()->delete();

        $days = [
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6,
            'sunday' => 7
        ];

        $timeSlots = [
            ['08:00', '09:30'],
            ['09:45', '11:15'],
            ['11:30', '13:00'],
            ['14:00', '15:30'],
            ['15:45', '17:15'],
            ['17:30', '19:00']
        ];

        $slotsCreated = 0;

        // Créer des créneaux basés sur les heures par semaine
        foreach ($assignments as $assignment) {
            $hoursPerWeek = $assignment->hours_per_week;
            $hoursPerSession = 1.5; // 1h30 par session
            
            $sessionsNeeded = ceil($hoursPerWeek / $hoursPerSession);
            
            // Répartir sur les jours disponibles
            if ($assignment->day_of_week && isset($days[$assignment->day_of_week])) {
                // Jour spécifique
                $dayNumber = $days[$assignment->day_of_week];
                $this->createSlotsForDay($assignment, $dayNumber, $sessionsNeeded, $timeSlots);
            } else {
                // Répartir automatiquement sur plusieurs jours
                $availableDays = [1, 2, 3, 4, 5]; // Lundi à Vendredi
                shuffle($availableDays);
                
                $sessionsPerDay = ceil($sessionsNeeded / count($availableDays));
                
                foreach ($availableDays as $dayNumber) {
                    if ($sessionsNeeded <= 0) break;
                    
                    $sessionsToCreate = min($sessionsPerDay, $sessionsNeeded);
                    $this->createSlotsForDay($assignment, $dayNumber, $sessionsToCreate, $timeSlots);
                    
                    $sessionsNeeded -= $sessionsToCreate;
                }
            }
            
            $slotsCreated++;
        }

        return $slotsCreated;
    }

    /**
     * Créer des créneaux pour un jour spécifique
     */
    private function createSlotsForDay($assignment, $dayNumber, $sessionsCount, $timeSlots)
    {
        $colors = [
            '#3B82F6', // Bleu
            '#10B981', // Vert
            '#8B5CF6', // Violet
            '#F59E0B', // Orange
            '#EF4444', // Rouge
            '#06B6D4', // Cyan
            '#EC4899', // Rose
        ];

        $subjectId = $assignment->subject_id;
        $colorIndex = $subjectId % count($colors);
        $color = $colors[$colorIndex];

        // Trouver des créneaux disponibles
        $occupiedSlots = $this->timetableSlots()
            ->where('day_of_week', $dayNumber)
            ->pluck('start_time')
            ->toArray();

        $slotsUsed = 0;
        
        foreach ($timeSlots as $index => $timeSlot) {
            if ($slotsUsed >= $sessionsCount) break;
            
            $startTime = $timeSlot[0];
            
            // Vérifier si le créneau est déjà occupé
            if (!in_array($startTime, $occupiedSlots)) {
                $this->timetableSlots()->create([
                    'day_of_week' => $dayNumber,
                    'start_time' => $startTime,
                    'end_time' => $timeSlot[1],
                    'subject_id' => $assignment->subject_id,
                    'teacher_id' => $assignment->teacher_id,
                    'classroom_id' => null,
                    'color' => $color,
                    'assignment_id' => $assignment->id,
                    'sequence_order' => $index,
                    'tenant_id' => $this->tenant_id,
                ]);
                
                $occupiedSlots[] = $startTime;
                $slotsUsed++;
            }
        }
    }

    /**
     * Récupérer les heures par matière
     */
    public function getSubjectHours()
    {
        return $this->timetableSlots()
            ->join('subjects', 'timetable_slots.subject_id', '=', 'subjects.id')
            ->select('subjects.name', 'subjects.code')
            ->selectRaw('COUNT(*) * 1.5 as total_hours') // 1.5h par créneau
            ->groupBy('subjects.id', 'subjects.name', 'subjects.code')
            ->get();
    }

    /**
     * Récupérer l'emploi du temps d'un professeur
     */
    public function getTeacherSchedule($teacherId)
    {
        return $this->timetableSlots()
            ->where('teacher_id', $teacherId)
            ->with(['subject', 'classroom'])
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_name');
    }

    /**
     * Calculer le total d'heures de l'emploi du temps
     */
    public function getTotalHours()
    {
        return $this->timetableSlots->sum(function($slot) {
            $start = strtotime($slot->start_time);
            $end = strtotime($slot->end_time);
            return ($end - $start) / 3600;
        });
    }

    /**
     * Vérifier si l'emploi du temps est valide (sans conflits)
     */
    public function isValid()
    {
        return $this->checkConflicts()->isEmpty();
    }

    /**
     * Dupliquer l'emploi du temps
     */
    public function duplicate($newName = null)
    {
        $newTimetable = $this->replicate();
        $newTimetable->name = $newName ?? $this->name . ' - Copie';
        $newTimetable->created_by = auth()->id();
        $newTimetable->save();

        // Dupliquer les créneaux
        foreach ($this->timetableSlots as $slot) {
            $newSlot = $slot->replicate();
            $newSlot->timetable_id = $newTimetable->id;
            $newSlot->save();
        }

        return $newTimetable;
    }
}