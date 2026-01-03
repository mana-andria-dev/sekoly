<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'code',
        'name',
        'description',
        'level',
        'hours_per_week',
        'coefficient',
        'is_active',
        'metadata'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'coefficient' => 'float',
        'hours_per_week' => 'integer',
        'metadata' => 'array'
    ];

    // ========== RELATIONS ==========
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function classAssignments()
    {
        return $this->hasMany(ClassAssignment::class, 'subject_id');
    }    

    public function assignedClasses()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_assignments', 'subject_id', 'class_id')
                    ->withPivot(['teacher_id', 'hours_per_week', 'coefficient', 'is_active'])
                    ->withTimestamps();
    }    

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'teacher_id')
                    ->where('users.role', 'teacher') // Garder comme condition dans la relation
                    ->withTimestamps();
    }

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
                    ->withTimestamps();
    }

    // ========== SCOPES ==========
    
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    public function scopeByLevel($query, $level)
    {
        if ($level) {
            return $query->where('level', $level);
        }
        return $query;
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }
        return $query;
    }

    // ========== ATTRIBUTES ==========
    
    public function getFormattedCoefficientAttribute()
    {
        return number_format($this->coefficient, 1);
    }

    public function getLevelLabelAttribute()
    {
        return match($this->level) {
            'maternelle' => 'Maternelle',
            'primaire' => 'Primaire',
            'college' => 'Collège',
            'lycee' => 'Lycée',
            default => 'Non spécifié'
        };
    }

    public function getStatusBadgeAttribute()
    {
        return $this->is_active 
            ? '<span class="px-2 py-1 text-xs font-medium rounded-full bg-green-600/10 text-green-500">Active</span>'
            : '<span class="px-2 py-1 text-xs font-medium rounded-full bg-red-600/10 text-red-500">Inactive</span>';
    }

    // ========== METHODS ==========
    
    public static function generateCode($name)
    {
        $prefix = strtoupper(substr(preg_replace('/[^A-Z]/', '', $name), 0, 3));
        $number = self::where('code', 'like', $prefix . '%')->count() + 1;
        return $prefix . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}