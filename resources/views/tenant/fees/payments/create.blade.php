@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('fees.payments.index') }}" class="text-gray-400 hover:text-white mr-4">
            ← Retour
        </a>
        <h1 class="text-2xl font-bold text-white">Nouveau paiement</h1>
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <form method="POST" action="{{ route('fees.payments.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Élève -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Élève *</label>
                    <select name="student_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        <option value="">Sélectionner un élève</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Structure de frais -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Motif du paiement *</label>
                    <select name="fee_structure_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        <option value="">Sélectionner un motif</option>
                        @foreach($feeStructures as $structure)
                            <option value="{{ $structure->id }}">
                                {{ $structure->name }} - {{ number_format($structure->amount, 0, ',', ' ') }} Ariary
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Année scolaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Année scolaire *</label>
                    <select name="school_year_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ $year->is_active ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Montant total -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Montant total (Ariary) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                </div>
                
                <!-- Montant payé -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Montant payé (Ariary) *</label>
                    <input type="number" name="paid_amount" value="{{ old('paid_amount') }}" step="0.01"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                </div>
                
                <!-- Date d'échéance -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date d'échéance *</label>
                    <input type="date" name="due_date" value="{{ old('due_date', date('Y-m-d')) }}"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                </div>
                
                <!-- Méthode de paiement -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Méthode de paiement</label>
                    <select name="payment_method" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Sélectionner</option>
                        @foreach($paymentMethods as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Transaction ID -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">ID Transaction</label>
                    <input type="text" name="transaction_id" value="{{ old('transaction_id') }}"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white"
                           placeholder="Référence bancaire / numéro chèque">
                </div>
                
                <!-- Notes -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">{{ old('notes') }}</textarea>
                </div>
            </div>
            
            <!-- Informations sur le solde -->
            <div class="mt-6 p-4 bg-gray-850 rounded-lg">
                <h3 class="text-sm font-medium text-gray-300 mb-2">Récapitulatif</h3>
                <div class="text-sm text-gray-400">
                    <p>• Le statut sera calculé automatiquement selon le montant payé</p>
                    <p>• Une facture unique sera générée automatiquement</p>
                    <p>• Le solde de l'élève sera mis à jour</p>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-800">
                <a href="{{ route('fees.payments.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white">
                    Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Script pour remplir automatiquement le montant quand on sélectionne une structure
document.querySelector('[name="fee_structure_id"]').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const amountInput = document.querySelector('[name="amount"]');
    
    if (selectedOption.value) {
        // Extraire le montant du texte de l'option (format: "Nom - XXXX Ariary")
        const text = selectedOption.text;
        const match = text.match(/(\d+(?:[\s]?\d+)?)\s*Ariary/);
        if (match) {
            amountInput.value = match[1].replace(/\s/g, '');
        }
    }
});

// Valider que le montant payé ne dépasse pas le montant total
document.querySelector('[name="paid_amount"]').addEventListener('input', function() {
    const amount = parseFloat(document.querySelector('[name="amount"]').value) || 0;
    const paidAmount = parseFloat(this.value) || 0;
    
    if (paidAmount > amount) {
        this.value = amount;
        alert('Le montant payé ne peut pas dépasser le montant total');
    }
});

const paymentMethodSelect = document.querySelector('[name="payment_method"]');
const transactionIdInput = document.querySelector('[name="transaction_id"]');
const paidAmountInput = document.querySelector('[name="paid_amount"]');

function generateTransactionId() {
    const paymentMethod = paymentMethodSelect.value;
    const prefixes = {
        'bank_transfer': 'VIREMENT',
        'check': 'CHEQUE',
        'mobile_money': 'MMONEY',
        'cash': 'ESPECE'
    };
    const prefix = prefixes[paymentMethod] || 'TRX';
    const date = new Date().toISOString().slice(0, 10).replace(/-/g, '');
    const random = Math.random().toString(36).substring(2, 8).toUpperCase();
    return `${prefix}-${date}-${random}`;
}

// Mettre à jour le transaction_id automatiquement
function updateTransactionId() {
    const paidAmount = parseFloat(paidAmountInput.value) || 0;
    const currentValue = transactionIdInput.value;
    
    // Générer automatiquement seulement si le champ est vide
    if (paidAmount > 0 && (!currentValue || currentValue === '')) {
        transactionIdInput.value = generateTransactionId();
    } else if (paidAmount === 0) {
        transactionIdInput.value = '';
    }
}

paymentMethodSelect.addEventListener('change', function() {
    updateTransactionId();
});

paidAmountInput.addEventListener('input', function() {
    updateTransactionId();
});
</script>
@endsection