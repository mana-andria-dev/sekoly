<?php
// app/Models/TenantUser.php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TenantUser extends Authenticatable
{
    use Notifiable;
    
    // Ne pas spécifier de connexion ici, elle sera dynamique
    protected $table = 'users';
    
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];
    
    protected $hidden = [
        'password',
        'remember_token',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
    ];
    
    // Override pour utiliser la connexion dynamique
    public function getConnectionName()
    {
        return config('database.default');
    }
}