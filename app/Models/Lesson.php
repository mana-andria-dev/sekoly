<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lesson extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'tenant_id', 'class_id', 'subject_id', 'teacher_id',
        'title', 'description', 'content', 'lesson_date',
        'start_time', 'end_time', 'type', 'status',
        'resources', 'objectives'
    ];
    
    protected $casts = [
        'lesson_date' => 'date',
        'resources' => 'array',
        'objectives' => 'array',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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
}