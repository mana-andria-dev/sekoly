<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Classroom extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'tenant_id',
        'name',
        'code',
        'capacity',
        'type',
        'equipment',
        'is_active',
        'location',
        'floor',
        'building'
    ];

    protected $casts = [
        'capacity' => 'integer',
        'is_active' => 'boolean',
        'equipment' => 'array'
    ];

    // Relations
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function timetableSlots()
    {
        return $this->hasMany(TimetableSlot::class);
    }

    // Scopes
    public function scopeForTenant($query, $tenantId = null)
    {
        return $query->where('tenant_id', $tenantId ?? app('tenant')->id);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return "{$this->name} ({$this->code})";
    }

    public function getAvailabilityStatusAttribute()
    {
        return $this->is_active ? 'Disponible' : 'Indisponible';
    }
}