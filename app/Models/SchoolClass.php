<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SchoolClass extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'school_year_id', 
        'name'
    ];    

    // Ajoutez cette méthode
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? tenant()->id);
    }

    // Ajoutez aussi cette méthode pour les classes actives
    public function scopeActive($query)
    {
        // Vous pouvez ajouter une logique si nécessaire
        // Par exemple, vérifier si l'année scolaire est active
        return $query;
    }

    // Ajoutez la relation avec assignments
    public function assignments()
    {
        return $this->hasMany(ClassAssignment::class, 'class_id');
    }

    public function assignedSubjects()
    {
        return $this->belongsToMany(Subject::class, 'class_assignments', 'class_id', 'subject_id')
                    ->withPivot(['teacher_id', 'hours_per_week', 'coefficient', 'is_active'])
                    ->withTimestamps();
    }

    public function assignedTeachers()
    {
        return $this->belongsToMany(User::class, 'class_assignments', 'class_id', 'teacher_id')
                    ->where('users.role', 'teacher')
                    ->withPivot(['subject_id', 'hours_per_week', 'coefficient', 'is_active'])
                    ->withTimestamps();
    }

    // Relation avec l'année scolaire
    public function year()
    {
        return $this->belongsTo(SchoolYear::class, 'school_year_id');
    }

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class, 'class_id');
    }

    // public function students()
    // {
    //     return $this->belongsToMany(User::class, 'student_enrollments', 'class_id', 'student_id')
    //                 ->where('users.role', 'student')
    //                 ->withTimestamps();
    // }

    public function students()
    {
        return $this->belongsToMany(User::class, 'student_enrollments', 'class_id', 'student_id')
                    ->where('role', 'student')
                    ->wherePivot('deleted_at', null)
                    ->withTimestamps();
    }    
}