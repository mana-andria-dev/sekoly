<?php
// app/Models/ExamResult.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ExamResult extends Model
{
    use SoftDeletes;
    
    protected $table = 'exam_results';
    
    protected $fillable = [
        'exam_id', 
        'student_id', 
        'score', 
        'feedback', 
        'details', 
        'recorded_at', 
        'recorded_by'
    ];
    
    protected $casts = [
        'score' => 'float',
        'details' => 'array',
        'recorded_at' => 'datetime',
        'graded_at' => 'datetime'
    ];
    
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
    
    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}