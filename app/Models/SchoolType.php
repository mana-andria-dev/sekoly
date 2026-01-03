<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolType extends Model
{
    protected $fillable = ['name', 'code'];

    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
