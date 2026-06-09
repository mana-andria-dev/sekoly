@extends('layouts.admin')

@section('title', 'Ajouter un abonnement')
@section('subtitle', $tenant->name)

@section('content')
<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-semibold text-white">Nouvel abonnement pour {{ $tenant->name }}</h3>
    </div>
    
    <form method="POST" action="{{ route('admin.subscriptions.store', $tenant->id) }}">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Informations école -->
            <div class="bg-gray-850 rounded-lg p-4 mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-600 to-info rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ substr($tenant->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <p class="text-white font-semibold">{{ $tenant->name }}</p>
                        <p class="text-sm text-gray-400">{{ $tenant->email }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Choix du plan -->
            <div>
                <label for="plan" class="block text-sm font-medium text-gray-300 mb-2">Formule d'abonnement *</label>
                <select name="plan" 
                        id="plan" 
                        class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('plan') border-red-500 @enderror"
                        required>
                    <option value="">Sélectionnez une formule</option>
                    <option value="basic" {{ old('plan') == 'basic' ? 'selected' : '' }}>Basic - 99€/mois</option>
                    <option value="premium" {{ old('plan') == 'premium' ? 'selected' : '' }}>Premium - 199€/mois</option>
                    <option value="enterprise" {{ old('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise - 299€/mois</option>
                </select>
                @error('plan')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Période -->
            <div>
                <label for="period" class="block text-sm font-medium text-gray-300 mb-2">Période d'abonnement *</label>
                <select name="period" 
                        id="period" 
                        class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600"
                        required>
                    <option value="monthly">Mensuel</option>
                    <option value="quarterly">Trimestriel (3 mois)</option>
                    <option value="yearly">Annuel (12 mois)</option>
                </select>
            </div>
            
            <!-- Montant (calculé automatiquement) -->
            <div>
                <label for="amount" class="block text-sm font-medium text-gray-300 mb-2">Montant (€)</label>
                <input type="number" 
                       name="amount" 
                       id="amount" 
                       value="{{ old('amount') }}"
                       step="0.01"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600"
                       readonly>
                <p class="mt-1 text-sm text-gray-400">Le montant sera calculé automatiquement selon la formule et la période</p>
            </div>
            
            <!-- Date de début -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-300 mb-2">Date de début *</label>
                <input type="date" 
                       name="start_date" 
                       id="start_date" 
                       value="{{ old('start_date', date('Y-m-d')) }}"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('start_date') border-red-500 @enderror"
                       required>
                @error('start_date')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Date de fin (calculée automatiquement) -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-300 mb-2">Date de fin</label>
                <input type="date" 
                       name="end_date" 
                       id="end_date" 
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600"
                       readonly>
                <p class="mt-1 text-sm text-gray-400">Calculée automatiquement</p>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-800 bg-gray-850 flex justify-end gap-3">
            <a href="{{ route('admin.schools.show', $tenant->id) }}" 
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                Créer l'abonnement
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const planSelect = document.getElementById('plan');
    const periodSelect = document.getElementById('period');
    const amountInput = document.getElementById('amount');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    
    const prices = {
        basic: { monthly: 99, quarterly: 270, yearly: 990 },
        premium: { monthly: 199, quarterly: 540, yearly: 1990 },
        enterprise: { monthly: 299, quarterly: 810, yearly: 2990 }
    };
    
    function calculateAmount() {
        const plan = planSelect.value;
        const period = periodSelect.value;
        
        if (plan && period && prices[plan] && prices[plan][period]) {
            amountInput.value = prices[plan][period];
        } else {
            amountInput.value = '';
        }
        
        calculateEndDate();
    }
    
    function calculateEndDate() {
        const startDate = startDateInput.value;
        const period = periodSelect.value;
        
        if (startDate && period) {
            const date = new Date(startDate);
            if (period === 'monthly') {
                date.setMonth(date.getMonth() + 1);
            } else if (period === 'quarterly') {
                date.setMonth(date.getMonth() + 3);
            } else if (period === 'yearly') {
                date.setFullYear(date.getFullYear() + 1);
            }
            endDateInput.value = date.toISOString().split('T')[0];
        }
    }
    
    planSelect.addEventListener('change', calculateAmount);
    periodSelect.addEventListener('change', calculateAmount);
    startDateInput.addEventListener('change', calculateEndDate);
    
    // Initial calculation
    calculateAmount();
});
</script>
@endsection