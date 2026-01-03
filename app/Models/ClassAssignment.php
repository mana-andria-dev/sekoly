<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClassAssignment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'class_id',
        'subject_id',
        'teacher_id',
        'hours_per_week',
        'coefficient',
        'day_of_week',
        'start_date',
        'end_date',
        'is_active',
        'status',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hours_per_week' => 'integer',
        'coefficient' => 'float',
        'start_date' => 'date',
        'end_date' => 'date',
        'metadata' => 'array'
    ];

    protected $attributes = [
        'status' => 'active',
        'is_active' => true,
        'hours_per_week' => 0,
        'coefficient' => 1.0
    ];

    // ========== MUTATEURS ==========
    
    /**
     * Assure que le statut est toujours en minuscules
     */
    public function setStatusAttribute($value)
    {
        $this->attributes['status'] = strtolower($value);
    }

    /**
     * Assure que le jour de la semaine est toujours en minuscules
     */
    public function setDayOfWeekAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['day_of_week'] = strtolower($value);
        } else {
            $this->attributes['day_of_week'] = null;
        }
    }

    /**
     * Formate correctement la date de début
     */
    public function setStartDateAttribute($value)
    {
        if ($value instanceof \Carbon\Carbon) {
            $this->attributes['start_date'] = $value->format('Y-m-d');
        } elseif (is_string($value)) {
            $this->attributes['start_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['start_date'] = $value;
        }
    }

    /**
     * Formate correctement la date de fin
     */
    public function setEndDateAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['end_date'] = null;
        } elseif ($value instanceof \Carbon\Carbon) {
            $this->attributes['end_date'] = $value->format('Y-m-d');
        } elseif (is_string($value)) {
            $this->attributes['end_date'] = \Carbon\Carbon::parse($value)->format('Y-m-d');
        } else {
            $this->attributes['end_date'] = $value;
        }
    }

    // ========== RELATIONS ==========
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
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
            'id', // Clé sur users
            'user_id', // Clé sur teachers
            'teacher_id', // Clé locale sur class_assignments
            'id' // Clé sur users
        );
    }    

    // ========== SCOPES ==========
    
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeCurrent($query)
    {
        return $query->where(function($q) {
            $q->whereNull('end_date')
              ->orWhere('end_date', '>=', now());
        });
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function scopeWithTeacher($query)
    {
        return $query->whereNotNull('teacher_id');
    }

    public function scopeWithoutTeacher($query)
    {
        return $query->whereNull('teacher_id');
    }

    // ========== VALIDATION RULES ==========
    
    public static function getValidationRules($id = null)
    {
        $rules = [
            'class_id' => 'required|exists:school_classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'nullable|exists:users,id',
            'hours_per_week' => 'required|integer|min:1|max:40',
            'coefficient' => 'required|numeric|min:0.1|max:10',
            'day_of_week' => 'nullable|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'status' => 'nullable|in:active,ended,pending',
            'is_active' => 'boolean',
        ];

        return $rules;
    }

    // ========== BUSINESS LOGIC ==========
    
    public function isCurrent()
    {
        return is_null($this->end_date) || $this->end_date >= now();
    }

    public function hasTeacher()
    {
        return !is_null($this->teacher_id);
    }

    public function deactivate()
    {
        $this->update([
            'is_active' => false,
            'status' => 'ended',
            'end_date' => $this->end_date ?? now()
        ]);
    }

    public function activate()
    {
        $this->update([
            'is_active' => true,
            'status' => 'active',
            'end_date' => null
        ]);
    }

    public function assignToTeacher($teacherId)
    {
        $this->update([
            'teacher_id' => $teacherId,
            'status' => 'active'
        ]);
    }

    public function unassignTeacher()
    {
        $this->update([
            'teacher_id' => null,
            'status' => 'pending'
        ]);
    }
}