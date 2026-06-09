<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportCard extends Model
{
    use SoftDeletes;
    
    protected $table = 'report_cards';
    
    protected $fillable = [
        'student_id', 'class_id', 'school_year_id', 'period',
        'subject_grades', 'overall_average', 'class_average', 'class_rank',
        'total_students', 'appreciation', 'teacher_comments', 'principal_comments',
        'absences', 'behaviors', 'issued_date', 'status'
    ];
    
    protected $casts = [
        'subject_grades' => 'array',
        'absences' => 'array',
        'behaviors' => 'array',
        'overall_average' => 'float',
        'class_average' => 'float',
        'issued_date' => 'date'
    ];
    
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    // Obtenir la mention
    public function getMentionAttribute()
    {
        if (!$this->overall_average) return 'Non évalué';
        
        if ($this->overall_average >= 16) return 'Très Bien';
        if ($this->overall_average >= 14) return 'Bien';
        if ($this->overall_average >= 12) return 'Assez Bien';
        if ($this->overall_average >= 10) return 'Passable';
        return 'Insuffisant';
    }
    
    // Obtenir la couleur de la mention
    public function getMentionColorAttribute()
    {
        if (!$this->overall_average) return 'gray';
        
        if ($this->overall_average >= 16) return 'purple';
        if ($this->overall_average >= 14) return 'blue';
        if ($this->overall_average >= 12) return 'green';
        if ($this->overall_average >= 10) return 'yellow';
        return 'red';
    }
    
    // Calculer le rang en lettre
    public function getRankOrdinalAttribute()
    {
        if (!$this->class_rank) return '-';
        
        $number = $this->class_rank;
        $suffixes = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];
        
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            $suffix = 'th';
        } else {
            $suffix = $suffixes[$number % 10];
        }
        
        return $number . $suffix;
    }
}