<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherContract extends Model
{
    protected $fillable = [
        'teacher_id', 'contract_number', 'contract_type', 'start_date', 'end_date',
        'salary', 'hourly_rate', 'hours_per_week', 'document_path', 'terms', 'status', 'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'salary' => 'decimal:2',
        'hourly_rate' => 'decimal:2',
        'terms' => 'array',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               ($this->end_date === null || $this->end_date >= now());
    }
}