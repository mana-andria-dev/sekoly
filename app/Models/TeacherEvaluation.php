<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherEvaluation extends Model
{
    protected $fillable = [
        'teacher_id', 'evaluator_id', 'evaluation_date', 'evaluation_type',
        'pedagogical_skills', 'subject_knowledge', 'classroom_management',
        'communication', 'punctuality', 'overall_rating',
        'strengths', 'improvements_needed', 'recommendations', 'document_path'
    ];

    protected $casts = [
        'evaluation_date' => 'date',
        'pedagogical_skills' => 'decimal:1',
        'subject_knowledge' => 'decimal:1',
        'classroom_management' => 'decimal:1',
        'communication' => 'decimal:1',
        'punctuality' => 'decimal:1',
        'overall_rating' => 'decimal:1',
    ];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function evaluator()
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }
}
