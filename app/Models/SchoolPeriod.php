<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolPeriod extends Model
{
    protected $fillable = [
        'school_year_id',
        'name',
        'start_date',
        'end_date',
        'order'
    ];

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
