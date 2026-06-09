<?php

namespace App\Models;

use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Illuminate\Database\Eloquent\Model;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains;

    // App\Models\Tenant
    protected $fillable = [
        'id', 'name', 'database', 'slug', 'email', 'address', 
        'phone', 'logo_path', 'school_type_id', 'data',
        'status', 'activated_at', 'suspended_at', 'activation_notes' // Ajout
    ];

    protected $casts = [
        'data' => 'array',
        'activated_at' => 'datetime',
        'suspended_at' => 'datetime',
    ];

    // Ajouter des scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }    
    
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'data',
    ];
    
    /**
     * Get the columns that should be selected from the database.
     */
    public static function getCustomColumns(): array
    {
        return [
            'id',
            'name',
            'database',
            'slug',
            'email',
            'address',
            'phone',
            'logo_path',
            'school_type_id',
            'created_at',
            'updated_at',
        ];
    }
    
    // Relations
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id');
    }
    
    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'id')
                    ->where('status', 'active')
                    ->where('ends_at', '>', now());
    }
    
    public function domains()
    {
        return $this->hasMany(Domain::class, 'tenant_id', 'id');
    }
    
    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }
    
    // Accesseurs pour les données JSON (pour compatibilité)
    public function getPlanAttribute()
    {
        return $this->data['plan'] ?? null;
    }
    
    public function getMaxStudentsAttribute()
    {
        return $this->data['max_students'] ?? null;
    }
    
    public function getMaxTeachersAttribute()
    {
        return $this->data['max_teachers'] ?? null;
    }
}