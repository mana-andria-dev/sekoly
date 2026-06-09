<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\SchoolYear;
use App\Models\SchoolClass;

class FeeStructure extends Model
{
    protected $table = 'fee_structures';
    
    protected $fillable = [
        'school_year_id',
        'class_id',
        'name',
        'type',
        'amount',
        'month',
        'due_date',
        'description',
        'is_required',
        'is_active',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
        'is_required' => 'boolean',
        'is_active' => 'boolean',
    ];
    
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    public function class()
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
    
    public function payments()
    {
        return $this->hasMany(FeePayment::class);
    }
    
    public static function getTypes()
    {
        return [
            'registration' => 'Frais d\'inscription',
            'monthly' => 'Mensualité',
            'exam' => 'Frais d\'examen',
            'activity' => 'Frais d\'activité',
            'other' => 'Autres frais',
        ];
    }
}