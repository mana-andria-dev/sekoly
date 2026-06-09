@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('fees.payments.index') }}" class="text-gray-400 hover:text-white mr-4">
                ← Retour
            </a>
            <h1 class="text-2xl font-bold text-white">Détails du paiement</h1>
        </div>
        <div class="flex gap-3">
            @if($feePayment->status !== 'paid')
            <a href="{{ route('fees.payments.edit', $feePayment->id) }}" 
               class="bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Ajouter un paiement
            </a>
            @endif
            <button onclick="window.print()" 
                    class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Imprimer
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informations principales -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Facture #{{ $feePayment->invoice_number }}</h2>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Élève</label>
                            <p class="text-white text-lg">{{ $feePayment->student->name }}</p>
                            <p class="text-sm text-gray-400">{{ $feePayment->student->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Classe</label>
                            <p class="text-white">
                                {{ $feePayment->student->latestEnrollment?->schoolClass?->name ?? 'Non assigné' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Motif</label>
                            <p class="text-white">{{ $feePayment->feeStructure->name }}</p>
                            <p class="text-sm text-gray-400">{{ ucfirst($feePayment->feeStructure->type) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Année scolaire</label>
                            <p class="text-white">{{ $feePayment->schoolYear->name }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Date d'échéance</label>
                            <p class="text-white">{{ $feePayment->due_date->format('d/m/Y') }}</p>
                            @if($feePayment->due_date < now() && $feePayment->status !== 'paid')
                                <span class="text-xs text-red-400">En retard</span>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Date de paiement</label>
                            <p class="text-white">{{ $feePayment->paid_date ? $feePayment->paid_date->format('d/m/Y') : 'Non payé' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Montants et statut -->
        <div>
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Récapitulatif financier</h2>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center pb-3 border-b border-gray-800">
                        <span class="text-gray-400">Montant total</span>
                        <span class="text-white text-xl font-bold">{{ number_format($feePayment->amount, 0, ',', ' ') }} Ariary</span>
                    </div>
                    <div class="flex justify-between items-center pb-3 border-b border-gray-800">
                        <span class="text-gray-400">Montant payé</span>
                        <span class="text-green-400 text-xl font-bold">{{ number_format($feePayment->paid_amount, 0, ',', ' ') }} Ariary</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Reste à payer</span>
                        <span class="text-yellow-400 text-xl font-bold">{{ number_format($feePayment->remaining_amount, 0, ',', ' ') }} Ariary</span>
                    </div>
                    
                    <div class="mt-4 pt-4 border-t border-gray-800">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-400">Statut</span>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-600/20 text-yellow-400',
                                    'partial' => 'bg-blue-600/20 text-blue-400',
                                    'paid' => 'bg-green-600/20 text-green-400',
                                    'overdue' => 'bg-red-600/20 text-red-400',
                                ];
                                $statusLabels = [
                                    'pending' => 'En attente',
                                    'partial' => 'Paiement partiel',
                                    'paid' => 'Payé',
                                    'overdue' => 'En retard',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-sm {{ $statusColors[$feePayment->status] }}">
                                {{ $statusLabels[$feePayment->status] }}
                            </span>
                        </div>
                    </div>
                    
                    @if($feePayment->payment_method)
                    <div class="mt-4 pt-4 border-t border-gray-800">
                        <label class="block text-sm font-medium text-gray-400 mb-1">Méthode de paiement</label>
                        <p class="text-white">{{ ucfirst(str_replace('_', ' ', $feePayment->payment_method)) }}</p>
                        @if($feePayment->transaction_id)
                            <p class="text-sm text-gray-400 mt-1">Réf: {{ $feePayment->transaction_id }}</p>
                        @endif
                    </div>
                    @endif
                    
                    @if($feePayment->notes)
                    <div class="mt-4 pt-4 border-t border-gray-800">
                        <label class="block text-sm font-medium text-gray-400 mb-1">Notes</label>
                        <p class="text-gray-300">{{ $feePayment->notes }}</p>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Solde total de l'élève -->
            @php
                $totalBalance = $feePayment->student->feeBalances()
                    ->where('school_year_id', $feePayment->school_year_id)
                    ->first();
            @endphp
            @if($totalBalance)
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden mt-6">
                <div class="p-6">
                    <h3 class="text-sm font-medium text-gray-400 mb-3">Solde total de l'élève</h3>
                    <div class="text-2xl font-bold text-white">
                        {{ number_format($totalBalance->balance, 0, ',', ' ') }} Ariary
                    </div>
                    <div class="text-sm text-gray-400 mt-2">
                        Total dû: {{ number_format($totalBalance->total_amount, 0, ',', ' ') }} Ariary<br>
                        Total payé: {{ number_format($totalBalance->total_paid, 0, ',', ' ') }} Ariary
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Historique des paiements de l'élève -->
    <div class="mt-8">
        <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-800">
                <h2 class="text-lg font-semibold text-white">Autres paiements de {{ $feePayment->student->name }}</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Facture</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Motif</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Montant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Payé</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Échéance</th>
                         </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($feePayment->student->feePayments()
                            ->where('id', '!=', $feePayment->id)
                            ->where('school_year_id', $feePayment->school_year_id)
                            ->orderBy('due_date', 'desc')
                            ->take(5)
                            ->get() as $payment)
                        <tr class="hover:bg-gray-850 transition-colors">
                            <td class="px-6 py-4 text-white">{{ $payment->invoice_number }}</td>
                            <td class="px-6 py-4 text-gray-300">{{ $payment->feeStructure->name }}</td>
                            <td class="px-6 py-4 text-white">{{ number_format($payment->amount, 0, ',', ' ') }} Ariary</td>
                            <td class="px-6 py-4 text-green-400">{{ number_format($payment->paid_amount, 0, ',', ' ') }} Ariary</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$payment->status] }}">
                                    {{ $statusLabels[$payment->status] }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-300">{{ $payment->due_date->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection