<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableSlot extends Model
{
    protected $fillable = [
        // 'tenant_id',
        'timetable_id',
        'day_of_week',
        'start_time',
        'end_time',
        'subject_id',
        'teacher_id',
        'classroom_id',
        'color',
        'notes',
        'assignment_id',
        'recurring',
        'sequence_order'
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'recurring' => 'boolean',
    ];

    // Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function timetable()
    {
        return $this->belongsTo(Timetable::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function teacherProfile()
    {
        return $this->hasOneThrough(
            Teacher::class,
            User::class,
            'id',
            'user_id',
            'teacher_id',
            'id'
        );
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function assignment()
    {
        return $this->belongsTo(ClassAssignment::class, 'assignment_id');
    }

    // ========== SCOPES ==========
    
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    public function scopeActiveTimetable($query)
    {
        return $query->whereHas('timetable', function($q) {
            $q->where('is_active', true);
        });
    }

    public function scopeForCurrentWeek($query)
    {
        // Filtrer pour la semaine actuelle (si vous avez une logique de dates)
        return $query;
    }

    ///////////////////////////////////////////////////////////////    

    // Accessors
    public function getDayNameAttribute()
    {
        $days = [
            1 => 'Lundi',
            2 => 'Mardi',
            3 => 'Mercredi',
            4 => 'Jeudi',
            5 => 'Vendredi',
            6 => 'Samedi',
            7 => 'Dimanche'
        ];
        
        return $days[$this->day_of_week] ?? 'Inconnu';
    }

    public function getDayShortAttribute()
    {
        return substr($this->day_name, 0, 3);
    }

    public function getDurationAttribute()
    {
        $start = strtotime($this->start_time);
        $end = strtotime($this->end_time);
        return ($end - $start) / 3600; // Durée en heures
    }

    public function getTimeRangeAttribute()
    {
        return $this->start_time->format('H:i') . ' - ' . $this->end_time->format('H:i');
    }

    public function scopeForDay($query, $dayNumber)
    {
        return $query->where('day_of_week', $dayNumber);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('day_of_week')
                    ->orderBy('start_time')
                    ->orderBy('sequence_order');
    }

    // Vérifier les conflits
    public function hasConflicts()
    {
        return self::where('timetable_id', $this->timetable_id)
            ->where('day_of_week', $this->day_of_week)
            ->where('id', '!=', $this->id)
            ->where(function($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                    ->orWhere(function($q) {
                        $q->where('start_time', '<=', $this->start_time)
                          ->where('end_time', '>=', $this->end_time);
                    });
            })
            ->exists();
    }

    // Vérifier les conflits de professeur
    public function hasTeacherConflicts()
    {
        if (!$this->teacher_id) return false;

        return self::where('timetable_id', $this->timetable_id)
            ->where('day_of_week', $this->day_of_week)
            ->where('teacher_id', $this->teacher_id)
            ->where('id', '!=', $this->id)
            ->where(function($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time]);
            })
            ->exists();
    }
}