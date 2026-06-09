<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\User;
use App\Models\Tenant\SchoolYear;

class FeeBalance extends Model
{
    protected $table = 'fee_balances';
    
    protected $fillable = [
        'student_id',
        'school_year_id',
        'total_amount',
        'total_paid',
        'balance',
    ];
    
    protected $casts = [
        'total_amount' => 'decimal:2',
        'total_paid' => 'decimal:2',
        'balance' => 'decimal:2',
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    public function updateBalance()
    {
        $this->balance = $this->total_amount - $this->total_paid;
        $this->save();
        
        return $this;
    }
}