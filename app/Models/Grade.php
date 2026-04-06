<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Grade extends Model
{
    use SoftDeletes;
    
    protected $table = 'grades';
    
    protected $fillable = [
        'tenant_id', 'student_id', 'subject_id', 'class_id', 'teacher_id',
        'title', 'grade_type', 'reference_id', 'reference_type', 'score',
        'max_score', 'coefficient', 'grade_date', 'comment', 'period'
    ];
    
    protected $casts = [
        'score' => 'float',
        'max_score' => 'float',
        'coefficient' => 'float',
        'grade_date' => 'date'
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
    
    public function reference()
    {
        return $this->morphTo();
    }
    
    // Calculer le pourcentage
    public function getPercentageAttribute()
    {
        return ($this->score / $this->max_score) * 100;
    }
    
    // Calculer la note sur 20
    public function getScoreOver20Attribute()
    {
        return ($this->score / $this->max_score) * 20;
    }
}