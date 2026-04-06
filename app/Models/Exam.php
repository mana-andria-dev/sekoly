<?php
// app/Models/Exam.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;
    
    protected $table = 'exams';
    
    protected $fillable = [
        'tenant_id', 'class_id', 'subject_id', 'teacher_id',
        'title', 'description', 'type', 'exam_date', 'start_time',
        'end_time', 'duration_minutes', 'max_score', 'coefficient',
        'location', 'topics', 'status', 'instructions'
    ];
    
    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'topics' => 'array',
        'instructions' => 'array',
        'max_score' => 'float',
        'coefficient' => 'float'
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }    
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }
}