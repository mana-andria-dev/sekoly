<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'email',
        'address',
        'phone',
        'logo_path',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function schoolYears()
    {
        return $this->hasMany(SchoolYear::class);
    }

    public function schoolType()
    {
        return $this->belongsTo(SchoolType::class);
    }

}
