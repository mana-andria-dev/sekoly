<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Teacher extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'teacher_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone',
        'address',
        'city',
        'country',
        'nationality',
        'id_number',
        'social_security_number',
        'academic_degree',
        'specialization',
        'hire_date',
        'employment_type',
        'status',
        'qualifications',
        'hourly_rate',
        'hours_per_week',
        'bank_name',
        'bank_account',
        'emergency_contact_name',
        'emergency_contact_phone',
        'emergency_contact_relation',
        'notes',
        'photo'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'hourly_rate' => 'decimal:2',
        'qualifications' => 'array',
    ];

    // Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects')
                    ->withPivot('experience_years', 'proficiency_level', 'is_primary')
                    ->withTimestamps();
    }

    public function availabilities()
    {
        return $this->hasMany(TeacherAvailability::class);
    }

    public function contracts()
    {
        return $this->hasMany(TeacherContract::class);
    }

    public function evaluations()
    {
        return $this->hasMany(TeacherEvaluation::class);
    }

    // public function assignments()
    // {
    //     return $this->hasMany(ClassAssignment::class, 'teacher_id');
    // }

    public function userAssignments()
    {
        return $this->hasManyThrough(
            ClassAssignment::class,
            User::class,
            'id', // Clé sur users
            'teacher_id', // Clé sur class_assignments
            'user_id', // Clé locale sur teachers
            'id' // Clé sur users
        );
    }

    public function assignments()
    {
        // Relation via user_id
        return $this->hasMany(ClassAssignment::class, 'teacher_id', 'user_id');
    }    

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_assignments', 'teacher_id', 'class_id')
                    ->distinct();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('status', 'on_leave');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('first_name', 'like', "%{$search}%")
              ->orWhere('last_name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('teacher_id', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getAgeAttribute()
    {
        return $this->date_of_birth->age;
    }

    public function getYearsOfServiceAttribute()
    {
        return $this->hire_date->diffInYears(now());
    }

    // Méthodes
    public function getCurrentContract()
    {
        return $this->contracts()
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                              ->orWhereDate('end_date', '>=', now());
                    })
                    ->first();
    }

    public function currentContract()
    {
        return $this->hasOne(TeacherContract::class)
                    ->where('status', 'active')
                    ->whereDate('start_date', '<=', now())
                    ->where(function ($query) {
                        $query->whereNull('end_date')
                              ->orWhereDate('end_date', '>=', now());
                    })
                    ->latest();
    }    

    // public function getWeeklyWorkload()
    // {
    //     return $this->assignments()
    //                 ->where('is_active', true) // Utiliser is_active au lieu de scope Active
    //                 ->sum('hours_per_week');
    // }

    public function getWeeklyWorkload()
    {
        return $this->assignments()
            ->active()
            ->current()
            ->sum('hours_per_week');
    }

    public function getActiveAssignments()
    {
        return $this->assignments()
            ->with(['subject', 'schoolClass'])
            ->active()
            ->current()
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function getTeachingSubjects()
    {
        return $this->assignments()
            ->with('subject')
            ->active()
            ->current()
            ->get()
            ->pluck('subject')
            ->unique('id');
    }    

    public function teacherSubjects()
    {
        return $this->belongsToMany(Subject::class, 'teacher_subjects', 'teacher_id', 'subject_id')
                    ->withPivot(['experience_years', 'proficiency_level', 'is_primary'])
                    ->withTimestamps();
    }

    // Pour récupérer les matières principales
    public function primarySubjects()
    {
        return $this->teacherSubjects()->wherePivot('is_primary', true);
    }

    // Pour récupérer les matières secondaires
    public function secondarySubjects()
    {
        return $this->teacherSubjects()->wherePivot('is_primary', false);
    }

    // Pour vérifier si un professeur peut enseigner une matière
    public function canTeachSubject($subjectId)
    {
        return $this->teacherSubjects()->where('subject_id', $subjectId)->exists();
    }    
}

// app/Models/TeacherSubject.php
class TeacherSubject extends Model
{
    protected $fillable = ['teacher_id', 'subject_id', 'experience_years', 'proficiency_level', 'is_primary'];
}

// app/Models/TeacherAvailability.php
class TeacherAvailability extends Model
{
    protected $fillable = ['teacher_id', 'day_of_week', 'start_time', 'end_time', 'is_recurring', 'valid_from', 'valid_until'];
}