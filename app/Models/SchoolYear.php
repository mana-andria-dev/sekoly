<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolYear extends Model
{
    use HasFactory;

    protected $guarded = [];
    
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }   

    public function periodType()
    {
        return $this->belongsTo(PeriodType::class);
    }

    public function periods()
    {
        return $this->hasMany(SchoolPeriod::class);
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

    public function scopeCurrent($query)
    {
        return $query->where('is_active', true);
    }

    // ========== ATTRIBUTES ==========
    
    public function getIsCurrentAttribute()
    {
        return $this->is_active;
    }

    public function getCurrentLabelAttribute()
    {
        return $this->is_active 
            ? '<span class="px-2 py-1 text-xs rounded-full bg-green-600/10 text-green-500">Année en cours</span>'
            : '<span class="px-2 py-1 text-xs rounded-full bg-gray-600/10 text-gray-500">Année passée</span>';
    }

    public function getFormattedDatesAttribute()
    {
        return $this->start_date->format('d/m/Y') . ' - ' . $this->end_date->format('d/m/Y');
    }
}