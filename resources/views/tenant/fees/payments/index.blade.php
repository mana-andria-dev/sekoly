@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion des paiements</h1>
            <p class="text-gray-400 mt-1">Suivez tous les paiements des élèves</p>
        </div>
        <a href="{{ route('fees.payments.create') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Nouveau paiement
        </a>
    </div>

    <!-- Cartes statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total facturé</p>
                    <p class="text-2xl font-bold text-white">{{ number_format($stats['total_amount'], 0, ',', ' ') }} Ariary</p>
                </div>
                <div class="w-10 h-10 bg-blue-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-xl">💰</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total encaissé</p>
                    <p class="text-2xl font-bold text-green-400">{{ number_format($stats['total_paid'], 0, ',', ' ') }} Ariary</p>
                </div>
                <div class="w-10 h-10 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-xl">✅</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Reste à payer</p>
                    <p class="text-2xl font-bold text-yellow-400">{{ number_format($stats['total_remaining'], 0, ',', ' ') }} Ariary</p>
                </div>
                <div class="w-10 h-10 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-xl">⏳</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Taux recouvrement</p>
                    <p class="text-2xl font-bold text-white">{{ $stats['collection_rate'] }}%</p>
                </div>
                <div class="w-10 h-10 bg-purple-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-xl">📊</span>
                </div>
            </div>
            <div class="w-full bg-gray-700 rounded-full h-2 mt-2">
                <div class="bg-primary-600 rounded-full h-2 transition-all" style="width: {{ $stats['collection_rate'] }}%"></div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">En attente / Retard</p>
                    <p class="text-2xl font-bold text-orange-400">{{ $stats['total_pending'] + $stats['total_overdue'] }}</p>
                </div>
                <div class="w-10 h-10 bg-red-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-xl">⚠️</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres améliorés avec recherche d'élève -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- NOUVEAU : Recherche par nom/prénom d'élève -->
                <div class="lg:col-span-1">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Rechercher un élève</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="student_search" 
                               value="{{ request('student_search') }}" 
                               placeholder="Nom, prénom ou email..."
                               class="w-full pl-10 pr-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-500 focus:border-primary-500 focus:ring-1 focus:ring-primary-500">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Classe</label>
                    <select name="class_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Tous les statuts</option>
                        @foreach($statuses as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Année scolaire</label>
                    <select name="school_year_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ $schoolYearId == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Filtrer
                    </button>
                    @if(request('student_search') || request('student_id') || request('class_id') || request('status') || request('school_year_id'))
                        <a href="{{ route('fees.payments.index') }}" 
                           class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
            
            <!-- Message indiquant le résultat de la recherche -->
            @if(request('student_search'))
            <div class="flex items-center gap-2 text-sm text-primary-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <span>Résultats pour : <strong>"{{ request('student_search') }}"</strong></span>
                @if($payments->total() > 0)
                    <span class="text-gray-400">({{ $payments->total() }} paiement(s) trouvé(s))</span>
                @else
                    <span class="text-yellow-400">(Aucun résultat)</span>
                @endif
            </div>
            @endif
        </form>
    </div>

    <!-- Liste des paiements -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Classe</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Motif</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Payé</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Reste</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Échéance</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($payments as $payment)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4">
                            <span class="text-white font-mono text-sm">{{ $payment->invoice_number }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-primary-600/20 rounded-full flex items-center justify-center">
                                    <span class="text-white text-sm">
                                        {{ $payment->student->first_name ? substr($payment->student->first_name, 0, 1) : substr($payment->student->name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <span class="text-white">{{ $payment->student->name }}</span>
                                    @if($payment->student->first_name)
                                        <span class="text-gray-400 text-xs block">{{ $payment->student->first_name }}</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">
                            {{ $payment->student->latestEnrollment?->schoolClass?->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $payment->feeStructure->name }}</td>
                        <td class="px-6 py-4 text-white">{{ number_format($payment->amount, 0, ',', ' ') }} Ariary</td>
                        <td class="px-6 py-4 text-green-400">{{ number_format($payment->paid_amount, 0, ',', ' ') }} Ariary</td>
                        <td class="px-6 py-4">
                            @if($payment->remaining_amount > 0)
                                <span class="text-yellow-400">{{ number_format($payment->remaining_amount, 0, ',', ' ') }} Ariary</span>
                            @else
                                <span class="text-gray-500">0 Ariary</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-300">{{ $payment->due_date->format('d/m/Y') }}</span>
                            @if($payment->due_date < now() && $payment->status !== 'paid')
                                <span class="ml-2 text-xs text-red-400">⚠️ Retard</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    'paid' => ['color' => 'bg-green-600/20 text-green-400', 'icon' => '✅'],
                                    'partial' => ['color' => 'bg-blue-600/20 text-blue-400', 'icon' => '⏳'],
                                    'pending' => ['color' => 'bg-yellow-600/20 text-yellow-400', 'icon' => '⏰'],
                                    'overdue' => ['color' => 'bg-red-600/20 text-red-400', 'icon' => '⚠️'],
                                ];
                                $config = $statusConfig[$payment->status] ?? ['color' => 'bg-gray-600/20 text-gray-400', 'icon' => '❓'];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $config['color'] }} flex items-center gap-1 w-fit">
                                <span>{{ $config['icon'] }}</span>
                                <span>{{ $statuses[$payment->status] ?? $payment->status }}</span>
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('fees.payments.show', $payment) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors"
                                   title="Voir détails">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @if($payment->status !== 'paid')
                                <a href="{{ route('fees.payments.edit', $payment) }}" 
                                   class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                   title="Ajouter un paiement">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p>Aucun paiement trouvé</p>
                                @if(request('student_search'))
                                    <p class="text-sm text-gray-500">Essayez avec d'autres termes de recherche</p>
                                @else
                                    <a href="{{ route('fees.payments.create') }}" class="text-primary-400 hover:text-primary-300">
                                        Créer le premier paiement
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $payments->links() }}
    </div>
</div>
@endsection