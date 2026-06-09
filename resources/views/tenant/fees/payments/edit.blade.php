@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('fees.payments.show', $feePayment) }}" class="text-gray-400 hover:text-white mr-4">
            ← Retour
        </a>
        <h1 class="text-2xl font-bold text-white">Ajouter un paiement</h1>
        <p class="text-gray-400 ml-4">Facture #{{ $feePayment->invoice_number }}</p>
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <!-- Récapitulatif de la facture -->
        <div class="mb-6 p-4 bg-gray-850 rounded-lg">
            <h3 class="text-lg font-semibold text-white mb-3">Récapitulatif de la facture</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                <div>
                    <span class="text-gray-400">Élève:</span>
                    <span class="text-white ml-2">{{ $feePayment->student->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Motif:</span>
                    <span class="text-white ml-2">{{ $feePayment->feeStructure->name }}</span>
                </div>
                <div>
                    <span class="text-gray-400">Montant total:</span>
                    <span class="text-white ml-2">{{ number_format($feePayment->amount, 0, ',', ' ') }} €</span>
                </div>
                <div>
                    <span class="text-gray-400">Déjà payé:</span>
                    <span class="text-green-400 ml-2">{{ number_format($feePayment->paid_amount, 0, ',', ' ') }} €</span>
                </div>
                <div>
                    <span class="text-gray-400">Reste à payer:</span>
                    <span class="text-yellow-400 ml-2 font-bold">{{ number_format($feePayment->remaining_amount, 0, ',', ' ') }} €</span>
                </div>
                <div>
                    <span class="text-gray-400">Date d'échéance:</span>
                    <span class="text-white ml-2">{{ $feePayment->due_date->format('d/m/Y') }}</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('fees.payments.update', $feePayment) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Montant à payer -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Montant à payer (€) *</label>
                    <input type="number" name="paid_amount" value="{{ old('paid_amount', 0) }}" step="0.01" 
                           max="{{ $feePayment->remaining_amount }}"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600" 
                           required>
                    <p class="text-sm text-gray-400 mt-1">Maximum: {{ number_format($feePayment->remaining_amount, 0, ',', ' ') }} €</p>
                </div>
                
                <!-- Méthode de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Méthode de paiement</label>
                    <select name="payment_method" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600">
                        <option value="">Sélectionner</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}" {{ old('payment_method', $feePayment->payment_method) == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- ID Transaction / Référence (auto-généré) -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">ID Transaction / Référence</label>
                    <input type="text" name="transaction_id" 
                           value="{{ old('transaction_id', $feePayment->transaction_id ?? '') }}"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600"
                           placeholder="Auto-généré si laissé vide">
                    <p class="text-sm text-gray-400 mt-1">Laissez vide pour génération automatique</p>
                </div>
                
                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600">{{ old('notes', $feePayment->notes) }}</textarea>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-800">
                <a href="{{ route('fees.payments.show', $feePayment) }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white transition-colors">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white transition-colors">
                    Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Validation du montant
document.querySelector('[name="paid_amount"]').addEventListener('input', function() {
    const max = parseFloat(this.getAttribute('max')) || 0;
    let value = parseFloat(this.value) || 0;
    
    if (value > max) {
        this.value = max;
        alert('Le montant ne peut pas dépasser le reste à payer (' + max.toLocaleString() + ' €)');
    }
});
</script>
@endsection