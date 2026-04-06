<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Homework extends Model
{
    use SoftDeletes;
    
    // Spécifier explicitement le nom de la table
    protected $table = 'homeworks';
    
    protected $fillable = [
        'tenant_id', 'class_id', 'subject_id', 'teacher_id',
        'title', 'description', 'due_date', 'due_time',
        'max_score', 'type', 'attachments', 'instructions', 'status'
    ];
    
    protected $casts = [
        'due_date' => 'date',
        'due_time' => 'datetime',
        'attachments' => 'array',
        'max_score' => 'integer'
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
    
    public function submissions()
    {
        return $this->hasMany(HomeworkSubmission::class);
    }
}