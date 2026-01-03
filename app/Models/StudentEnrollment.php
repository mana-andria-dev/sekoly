<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentEnrollment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'student_id',
        'class_id',
        'school_year_id',
        'enrollment_date',
        'status', // active, graduated, transferred, expelled, left
        'roll_number',
        'section',
        'remarks',
        'metadata'
    ];

    protected $casts = [
        'enrollment_date' => 'date:Y-m-d', // Format pour input HTML
        'metadata' => 'array',
        'deleted_at' => 'datetime',
    ];

    // Accessor pour le format français
    public function getFormattedEnrollmentDateAttribute()
    {
        return $this->enrollment_date ? $this->enrollment_date->format('d/m/Y') : null;
    }

    // Accessor pour l'input HTML
    public function getEnrollmentDateForInputAttribute()
    {
        return $this->enrollment_date ? $this->enrollment_date->format('Y-m-d') : null;
    }

    protected $dates = ['enrollment_date'];

    // ========== RELATIONS ==========
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function schoolClass()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    public function scopeForClass($query, $classId)
    {
        return $query->where('class_id', $classId);
    }

    public function scopeForSchoolYear($query, $schoolYearId)
    {
        return $query->where('school_year_id', $schoolYearId);
    }

    public function scopeCurrentYear($query)
    {
        return $query->whereHas('schoolYear', function($q) {
            $q->where('is_current', true);
        });
    }

    // ========== ATTRIBUTES ==========
    
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Actif',
            'graduated' => 'Diplômé',
            'transferred' => 'Transféré',
            'expelled' => 'Exclu',
            'left' => 'Démission',
            default => 'Inconnu'
        };
    }

    public function getStatusBadgeAttribute()
    {
        $colors = [
            'active' => 'bg-green-600/10 text-green-500',
            'graduated' => 'bg-blue-600/10 text-blue-500',
            'transferred' => 'bg-yellow-600/10 text-yellow-500',
            'expelled' => 'bg-red-600/10 text-red-500',
            'left' => 'bg-gray-600/10 text-gray-500'
        ];

        $color = $colors[$this->status] ?? $colors['active'];
        
        return '<span class="px-2 py-1 text-xs font-medium rounded-full ' . $color . '">'
            . $this->status_label . '</span>';
    }

}