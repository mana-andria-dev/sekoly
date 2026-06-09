@extends('tenant.layouts.app')

@section('title', 'Information abonnement')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Information abonnement</h1>
            <p class="text-gray-400 mt-1">Détails de votre abonnement actuel</p>
        </div>
        <a href="{{ route('tenant.dashboard') }}" class="text-gray-400 hover:text-white">
            ← Retour au dashboard
        </a>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Carte principale -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h2 class="text-lg font-semibold text-white">Abonnement actuel</h2>
                </div>
                
                @if($activeSubscription)
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-gray-400 text-sm">Plan actuel</p>
                            <p class="text-3xl font-bold text-white capitalize">{{ $activeSubscription->plan }}</p>
                        </div>
                        <div class="px-4 py-2 bg-green-600/20 rounded-lg">
                            <span class="text-green-400">✓ Actif</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-6 mb-6">
                        <div>
                            <p class="text-gray-400 text-sm">Montant</p>
                            <p class="text-xl font-bold text-white">{{ number_format($activeSubscription->amount, 0, ',', ' ') }} Ariary</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Date de début</p>
                            <p class="text-xl font-bold text-white">{{ $activeSubscription->starts_at->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Date d'expiration</p>
                            <p class="text-xl font-bold text-white {{ $activeSubscription->ends_at <= now()->addDays(30) ? 'text-yellow-400' : '' }}">
                                {{ $activeSubscription->ends_at->format('d/m/Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-sm">Jours restants</p>
                            <p class="text-xl font-bold text-white">
                                {{ now()->diffInDays($activeSubscription->ends_at) }} jours
                            </p>
                        </div>
                    </div>
                    
                    @if($activeSubscription->ends_at <= now()->addDays(30))
                        <div class="bg-yellow-600/10 border border-yellow-600/30 rounded-lg p-4">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-yellow-400">
                                    Votre abonnement expire bientôt. Merci de contacter l'administration pour le renouvellement.
                                </p>
                            </div>
                        </div>
                    @endif
                </div>
                @else
                <div class="p-6 text-center">
                    <div class="w-16 h-16 bg-yellow-600/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-gray-400">Aucun abonnement actif trouvé</p>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Sidebar -->
        <div>
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden mb-6">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Informations école</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-12 h-12 bg-primary-600/20 rounded-lg flex items-center justify-center">
                            <span class="text-xl">🏫</span>
                        </div>
                        <div>
                            <p class="text-white font-semibold">{{ $tenant->name }}</p>
                            <p class="text-sm text-gray-400">{{ $tenant->slug }}.site.test</p>
                        </div>
                    </div>
                    <div class="space-y-2 text-sm">
                        <p><span class="text-gray-400">Email:</span> <span class="text-white">{{ $tenant->email }}</span></p>
                        <p><span class="text-gray-400">Téléphone:</span> <span class="text-white">{{ $tenant->phone ?? 'Non renseigné' }}</span></p>
                        <p><span class="text-gray-400">Date création:</span> <span class="text-white">{{ $tenant->created_at->format('d/m/Y') }}</span></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Support</h3>
                </div>
                <div class="p-6">
                    <p class="text-gray-300 text-sm mb-4">
                        Une question sur votre abonnement ? Contactez notre équipe support.
                    </p>
                    <a href="mailto:contact@sekoly.com" 
                       class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Contacter le support
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Historique -->
    @if($subscriptionHistory->count() > 1)
    <div class="mt-6">
        <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
            <div class="p-6 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">Historique des abonnements</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Plan</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Montant</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Début</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Fin</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($subscriptionHistory as $sub)
                        <tr class="hover:bg-gray-850">
                            <td class="px-4 py-3 text-white capitalize">{{ $sub->plan }}</td>
                            <td class="px-4 py-3 text-white">{{ number_format($sub->amount, 0, ',', ' ') }} Ariary</td>
                            <td class="px-4 py-3 text-gray-300">{{ $sub->starts_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-300">{{ $sub->ends_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded-full text-xs 
                                    @if($sub->status == 'active') bg-green-600/20 text-green-400
                                    @elseif($sub->status == 'expired') bg-red-600/20 text-red-400
                                    @else bg-yellow-600/20 text-yellow-400 @endif">
                                    {{ ucfirst($sub->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection