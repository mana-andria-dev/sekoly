<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'tenant_id',
        'plan',
        'amount',
        'status',
        'starts_at',
        'ends_at',
    ];
    
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'amount' => 'decimal:2',
    ];
    
    // Relation inverse
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }
    
    // Vérifier si l'abonnement est actif
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at > now();
    }
    
    // Vérifier si l'abonnement expire bientôt
    public function isExpiringSoon(int $days = 30): bool
    {
        return $this->isActive() && $this->ends_at <= now()->addDays($days);
    }
}