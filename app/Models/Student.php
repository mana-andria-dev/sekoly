<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = [
        'tenant_id',
        'first_name',
        'last_name',
        'birth_date',
        'gender',
        'parent_name',
        'parent_phone',
        'parent_email'
    ];

    public function enrollments()
    {
        return $this->hasMany(StudentEnrollment::class);
    }
}