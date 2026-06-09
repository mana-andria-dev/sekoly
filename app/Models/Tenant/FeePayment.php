<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\SchoolYear;

class FeePayment extends Model
{
    protected $table = 'fee_payments';
    
    protected $fillable = [
        'student_id',
        'fee_structure_id',
        'school_year_id',
        'invoice_number',
        'amount',
        'paid_amount',
        'remaining_amount',
        'status',
        'due_date',
        'paid_date',
        'payment_method',
        'transaction_id',
        'notes',
        'receipt_path',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];
    
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    
    public function feeStructure()
    {
        return $this->belongsTo(FeeStructure::class);
    }
    
    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
    
    public static function getStatuses()
    {
        return [
            'pending' => 'En attente',
            'partial' => 'Partiel',
            'paid' => 'Payé',
            'overdue' => 'En retard',
            'cancelled' => 'Annulé',
        ];
    }
    
    public static function getPaymentMethods()
    {
        return [
            'cash' => 'Espèces',
            'bank_transfer' => 'Virement bancaire',
            'check' => 'Chèque',
            'mobile_money' => 'Mobile Money',
            'other' => 'Autre',
        ];
    }
}