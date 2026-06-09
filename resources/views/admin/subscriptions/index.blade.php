@extends('layouts.admin')

@section('title', 'Gestion des abonnements')
@section('subtitle', 'Liste de tous les abonnements')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <form method="GET" class="flex gap-2">
                <select name="plan" class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-600">
                    <option value="">Tous les plans</option>
                    <option value="basic" {{ request('plan') == 'basic' ? 'selected' : '' }}>Basic</option>
                    <option value="premium" {{ request('plan') == 'premium' ? 'selected' : '' }}>Premium</option>
                    <option value="enterprise" {{ request('plan') == 'enterprise' ? 'selected' : '' }}>Enterprise</option>
                </select>
                <select name="status" class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-600">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                    Filtrer
                </button>
                @if(request('plan') || request('status'))
                    <a href="{{ route('admin.subscriptions.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium transition-colors">
                        Réinitialiser
                    </a>
                @endif
            </form>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total abonnements</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $subscriptions->total() }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📊</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Abonnements actifs</p>
                    <p class="text-3xl font-bold text-green-400 mt-2">
                        {{ $subscriptions->where('status', 'active')->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Revenus mensuels</p>
                    <p class="text-3xl font-bold text-white mt-2">
                        {{ number_format($subscriptions->where('status', 'active')->sum('amount'), 0, ',', ' ') }} Ariary
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Expiration dans 30j</p>
                    <p class="text-3xl font-bold text-yellow-400 mt-2">
                        {{ $subscriptions->where('status', 'active')->filter(function($sub) {
                            return $sub->ends_at <= now()->addDays(30) && $sub->ends_at > now();
                        })->count() }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">⚠️</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des abonnements -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Période</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date création</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($subscriptions as $subscription)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-600 to-info rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold text-sm">{{ substr($subscription->tenant->name ?? '?', 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-white">{{ $subscription->tenant->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-400">{{ $subscription->tenant->email ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="capitalize px-2 py-1 rounded-full text-xs font-medium
                                @if($subscription->plan == 'basic') bg-blue-600/20 text-blue-400
                                @elseif($subscription->plan == 'premium') bg-purple-600/20 text-purple-400
                                @else bg-orange-600/20 text-orange-400
                                @endif">
                                {{ ucfirst($subscription->plan) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-white">{{ number_format($subscription->amount, 0, ',', ' ') }}  Ariary</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-300">
                                Début: {{ $subscription->starts_at->format('d/m/Y') }}
                            </div>
                            <div class="text-sm text-gray-300">
                                Fin: {{ $subscription->ends_at->format('d/m/Y') }}
                            </div>
                            @if($subscription->ends_at <= now()->addDays(30) && $subscription->ends_at > now())
                                <span class="text-xs text-yellow-400">Expire bientôt</span>
                            @endif
                         </td>
                        <td class="px-6 py-4">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Actif</span>
                            @elseif($subscription->status === 'cancelled')
                                <span class="px-2 py-1 bg-red-600/20 text-red-400 rounded-full text-xs">Annulé</span>
                            @else
                                <span class="px-2 py-1 bg-gray-600/20 text-gray-400 rounded-full text-xs">Expiré</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $subscription->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-3">
                                <a href="{{ route('admin.schools.show', $subscription->tenant_id) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors">
                                    Voir école
                                </a>
                                @if($subscription->status === 'active')
                                <form method="POST" action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" 
                                      onsubmit="return confirm('Annuler cet abonnement ?')"
                                      class="inline">
                                    @csrf
                                    @method('POST')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                        Annuler
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            Aucun abonnement trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $subscriptions->links() }}
    </div>
</div>
@endsection