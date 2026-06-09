@extends('tenant.layouts.app')

@section('title', 'Abonnement expiré')

@section('content')
<div class="min-h-screen bg-gray-950">
    <div class="w-full px-4 py-8">
        <!-- En-tête -->
        <div class="text-center mb-8">
            <div class="w-24 h-24 bg-red-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">Abonnement expiré</h1>
            <p class="text-gray-400">Votre école n'a pas d'abonnement actif</p>
        </div>
        
        <!-- Message principal -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden mb-6">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                        <span class="text-2xl">⚠️</span>
                    </div>
                    <h2 class="text-xl font-semibold text-white">Accès restreint</h2>
                </div>
                
                <p class="text-gray-300 mb-4">
                    Votre abonnement a expiré ou n'a pas été activé. Pour continuer à utiliser la plateforme, 
                    vous devez renouveler votre abonnement.
                </p>
                
                @if($activeSubscription && $activeSubscription->status === 'expired')
                    <div class="bg-red-600/10 border border-red-600/30 rounded-lg p-4 mb-4">
                        <p class="text-red-400">
                            <strong>Date d'expiration :</strong> {{ $activeSubscription->ends_at->format('d/m/Y') }}
                        </p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Historique des abonnements -->
        @if($subscriptionHistory->count() > 0)
        <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Historique des abonnements</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Plan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Montant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Période</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($subscriptionHistory as $sub)
                        <tr class="hover:bg-gray-850 transition-colors">
                            <td class="px-4 py-3 text-white capitalize">{{ $sub->plan }}</td>
                            <td class="px-4 py-3 text-white">{{ number_format($sub->amount, 0, ',', ' ') }} 000 Ariary</td>
                            <td class="px-4 py-3 text-gray-300 text-sm">
                                {{ $sub->starts_at->format('d/m/Y') }} - {{ $sub->ends_at->format('d/m/Y') }}
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-600/20 text-green-400',
                                        'expired' => 'bg-red-600/20 text-red-400',
                                        'cancelled' => 'bg-yellow-600/20 text-yellow-400',
                                    ];
                                @endphp
                                <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$sub->status] ?? 'bg-gray-600/20' }}">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        
        <!-- Actions -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="mailto:contact@sekoly.com?subject=Renouvellement abonnement - {{ $tenant->name }}" 
               class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium text-center transition-colors">
                Contacter l'administration
            </a>
            <a href="{{ route('tenant.logout') }}" 
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="px-6 py-3 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium text-center transition-colors">
                Se déconnecter
            </a>
            <form id="logout-form" method="POST" action="{{ route('tenant.logout') }}" class="hidden">
                @csrf
            </form>
        </div>
    </div>
</div>
@endsection