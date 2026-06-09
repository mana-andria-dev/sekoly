<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\FeePayment;
use App\Models\Tenant\FeeStructure;
use App\Models\Tenant\FeeBalance;
use App\Models\User;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\SchoolClass;

class FeePaymentController extends Controller
{

    public function index(Request $request)
    {
        $studentId = $request->get('student_id');
        $studentSearch = $request->get('student_search'); // Nouveau champ de recherche
        $status = $request->get('status');
        $classId = $request->get('class_id');
        $schoolYearId = $request->get('school_year_id', SchoolYear::where('is_active', true)->first()?->id);
        
        $payments = FeePayment::with(['student', 'feeStructure', 'schoolYear'])
            ->when($studentId, fn($q) => $q->where('student_id', $studentId))
            ->when($studentSearch, function($q) use ($studentSearch) {
                $q->whereHas('student', function($sub) use ($studentSearch) {
                    $sub->where('name', 'like', "%{$studentSearch}%")
                        ->orWhere('first_name', 'like', "%{$studentSearch}%")
                        ->orWhere('email', 'like', "%{$studentSearch}%");
                });
            })
            ->when($classId, function($q) use ($classId) {
                $q->whereHas('student', function($sub) use ($classId) {
                    $sub->whereHas('latestEnrollment', function($enroll) use ($classId) {
                        $enroll->where('class_id', $classId);
                    });
                });
            })
            ->when($status, fn($q) => $q->where('status', $status))
            ->when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))
            ->orderBy('due_date', 'desc')
            ->paginate(20);
        
        // Conserver la recherche dans la pagination
        $payments->appends($request->all());
        
        $students = User::students()->orderBy('name')->get();
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $statuses = FeePayment::getStatuses();
        
        // Récupérer les classes pour le filtre
        $classes = SchoolClass::when($schoolYearId, function($q) use ($schoolYearId) {
                $q->where('school_year_id', $schoolYearId);
            })
            ->orderBy('name')
            ->get();
        
        // Statistiques pour le dashboard
        $stats = [
            'total_amount' => FeePayment::when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))->sum('amount'),
            'total_paid' => FeePayment::when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))->sum('paid_amount'),
            'total_pending' => FeePayment::when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))
                ->whereIn('status', ['pending', 'partial'])
                ->count(),
            'total_overdue' => FeePayment::when($schoolYearId, fn($q) => $q->where('school_year_id', $schoolYearId))
                ->where('status', 'overdue')
                ->where('due_date', '<', now())
                ->count(),
        ];
        
        $stats['total_remaining'] = $stats['total_amount'] - $stats['total_paid'];
        $stats['collection_rate'] = $stats['total_amount'] > 0 
            ? round(($stats['total_paid'] / $stats['total_amount']) * 100, 2) 
            : 0;
        
        return view('tenant.fees.payments.index', compact(
            'payments', 'students', 'schoolYears', 'statuses', 
            'schoolYearId', 'classes', 'classId', 'stats', 'studentSearch'
        ));
    }

    public function create(Request $request)
    {
        $studentId = $request->get('student_id');
        $student = $studentId ? User::findOrFail($studentId) : null;
        
        $students = User::students()->orderBy('name')->get();
        $feeStructures = FeeStructure::where('is_active', true)->get();
        $schoolYears = SchoolYear::orderBy('start_date', 'desc')->get();
        $paymentMethods = FeePayment::getPaymentMethods();
        
        return view('tenant.fees.payments.create', compact('students', 'feeStructures', 'schoolYears', 'paymentMethods', 'student'));
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'fee_structure_id' => 'required|exists:fee_structures,id',
            'school_year_id' => 'required|exists:school_years,id',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        // Calculer le reste à payer
        $remainingAmount = $validated['amount'] - $validated['paid_amount'];
        
        // Déterminer le statut
        $status = 'pending';
        if ($validated['paid_amount'] >= $validated['amount']) {
            $status = 'paid';
        } elseif ($validated['paid_amount'] > 0) {
            $status = 'partial';
        }
        
        // Générer le numéro de facture
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . strtoupper(Str::random(6));
        
        // Générer automatiquement l'ID Transaction si non fourni
        $transactionId = $validated['transaction_id'];
        if (empty($transactionId) && $validated['paid_amount'] > 0) {
            $transactionId = $this->generateTransactionId($validated['payment_method']);
        }
        
        $payment = FeePayment::create([
            'student_id' => $validated['student_id'],
            'fee_structure_id' => $validated['fee_structure_id'],
            'school_year_id' => $validated['school_year_id'],
            'invoice_number' => $invoiceNumber,
            'amount' => $validated['amount'],
            'paid_amount' => $validated['paid_amount'],
            'remaining_amount' => $remainingAmount,
            'status' => $status,
            'due_date' => $validated['due_date'],
            'paid_date' => $validated['paid_amount'] > 0 ? now() : null,
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
            'notes' => $validated['notes'],
        ]);
        
        // Mettre à jour ou créer le solde de l'élève
        $balance = FeeBalance::firstOrNew([
            'student_id' => $validated['student_id'],
            'school_year_id' => $validated['school_year_id'],
        ]);
        
        $balance->total_amount += $validated['amount'];
        $balance->total_paid += $validated['paid_amount'];
        $balance->balance = $balance->total_amount - $balance->total_paid;
        $balance->save();
        
        return redirect()->route('fees.payments.show', $payment)
            ->with('success', 'Paiement enregistré avec succès. Facture #' . $invoiceNumber);
    }
    
    public function show($id)
    {
        $feePayment = FeePayment::findOrFail($id);
        return view('tenant.fees.payments.show', compact('feePayment'));
    }
    
    public function edit($id)
    {
        $feePayment = FeePayment::findOrFail($id);
        $paymentMethods = FeePayment::getPaymentMethods();
        return view('tenant.fees.payments.edit', compact('feePayment', 'paymentMethods'));
    }

/**
     * Génère un ID de transaction automatique basé sur la méthode de paiement
     */
    private function generateTransactionId($paymentMethod = null)
    {
        $prefix = match($paymentMethod) {
            'bank_transfer' => 'VIREMENT',
            'check' => 'CHEQUE',
            'mobile_money' => 'MMONEY',
            'cash' => 'ESPECE',
            default => 'TRX'
        };
        
        return $prefix . '-' . date('Ymd') . '-' . strtoupper(Str::random(8));
    }    
    
    public function update(Request $request, $id)
    {
        $feePayment = FeePayment::findOrFail($id);

        $validated = $request->validate([
            'paid_amount' => 'required|numeric|min:0|max:' . $feePayment->remaining_amount,
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        
        $oldBalance = FeeBalance::where('student_id', $feePayment->student_id)
            ->where('school_year_id', $feePayment->school_year_id)
            ->first();
        
        // Générer l'ID Transaction si non fourni
        $transactionId = $validated['transaction_id'];
        if (empty($transactionId) && $validated['paid_amount'] > 0) {
            $transactionId = $this->generateTransactionId($validated['payment_method']);
        } elseif (empty($transactionId)) {
            $transactionId = $feePayment->transaction_id;
        }
        
        // Mettre à jour le paiement
        $newPaidAmount = $feePayment->paid_amount + $validated['paid_amount'];
        $newRemainingAmount = $feePayment->amount - $newPaidAmount;
        
        $status = 'pending';
        if ($newPaidAmount >= $feePayment->amount) {
            $status = 'paid';
        } elseif ($newPaidAmount > 0) {
            $status = 'partial';
        }
        
        $feePayment->update([
            'paid_amount' => $newPaidAmount,
            'remaining_amount' => $newRemainingAmount,
            'status' => $status,
            'paid_date' => $newPaidAmount > 0 ? now() : null,
            'payment_method' => $validated['payment_method'],
            'transaction_id' => $transactionId,
            'notes' => $validated['notes'],
        ]);
        
        // Mettre à jour le solde
        if ($oldBalance) {
            $oldBalance->total_paid += $validated['paid_amount'];
            $oldBalance->balance = $oldBalance->total_amount - $oldBalance->total_paid;
            $oldBalance->save();
        }
        
        return redirect()->route('fees.payments.show', $feePayment)
            ->with('success', 'Paiement ajouté avec succès. Réf: ' . ($transactionId ?? 'Non spécifiée'));
    }
    
    public function studentBalance($studentId)
    {
        $student = User::findOrFail($studentId);
        $currentYear = SchoolYear::where('is_active', true)->first();
        
        $balances = FeeBalance::with('schoolYear')
            ->where('student_id', $studentId)
            ->orderBy('created_at', 'desc')
            ->get();
        
        $payments = FeePayment::with('feeStructure')
            ->where('student_id', $studentId)
            ->where('school_year_id', $currentYear?->id)
            ->orderBy('due_date', 'desc')
            ->get();
        
        return view('tenant.fees.students.balance', compact('student', 'balances', 'payments', 'currentYear'));
    }
}