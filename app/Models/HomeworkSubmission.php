<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkSubmission extends Model
{
    use SoftDeletes;
    
    protected $table = 'homework_submissions';
    
    protected $fillable = [
        'homework_id', 'student_id', 'submission_text', 'attachments',
        'score', 'feedback', 'submitted_at', 'graded_at', 'status'
    ];
    
    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
        'attachments' => 'array',
        'score' => 'float'
    ];
    
    public function homework()
    {
        return $this->belongsTo(Homework::class);
    }
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}