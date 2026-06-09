@extends('layouts.admin')

@section('title', 'Détails de l\'école')
@section('subtitle', $school->name)

@section('content')
<div class="space-y-6">
    <!-- Actions -->
    <div class="flex justify-between items-center">
        <div>
            <a href="{{ route('admin.schools.index') }}" class="text-gray-400 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour à la liste
            </a>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.schools.edit', $school->id) }}" 
               class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 rounded-lg text-white font-medium transition-colors">
                Modifier
            </a>
            <a href="{{ route('admin.subscriptions.create', $school->id) }}" 
               class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-white font-medium transition-colors">
                + Ajouter un abonnement
            </a>
            <form method="POST" action="{{ route('admin.schools.destroy', $school->id) }}" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg text-white font-medium transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>

    <!-- Bandeau de statut et actions rapides -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div class="flex items-center gap-4">
                    <div>
                        <span class="text-gray-400 text-sm">Statut actuel</span>
                        <div class="mt-1">
                            @if($school->status === 'active')
                                <span class="px-3 py-1 bg-green-600/20 text-green-400 rounded-full text-sm font-medium">
                                    ✅ Actif
                                </span>
                            @elseif($school->status === 'pending')
                                <span class="px-3 py-1 bg-yellow-600/20 text-yellow-400 rounded-full text-sm font-medium">
                                    ⏳ En attente d'activation
                                </span>
                            @elseif($school->status === 'suspended')
                                <span class="px-3 py-1 bg-red-600/20 text-red-400 rounded-full text-sm font-medium">
                                    ⛔ Suspendu
                                </span>
                            @else
                                <span class="px-3 py-1 bg-gray-600/20 text-gray-400 rounded-full text-sm font-medium">
                                    {{ ucfirst($school->status) }}
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($school->activated_at)
                    <div>
                        <span class="text-gray-400 text-sm">Date d'activation</span>
                        <p class="text-white text-sm mt-1">{{ $school->activated_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                </div>
                
                <div class="flex gap-3">
                    @if($school->status === 'pending')
                        <form method="POST" action="{{ route('admin.schools.activate', $school->id) }}" 
                              onsubmit="return confirm('Activer cette école ? Un email avec les identifiants sera envoyé automatiquement.')">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-white font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Activer l'école
                            </button>
                        </form>
                    @elseif($school->status === 'active')
                        <form method="POST" action="{{ route('admin.schools.deactivate', $school->id) }}" 
                              onsubmit="return confirm('Désactiver cette école ? L\'accès sera immédiatement bloqué.')">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-orange-600 hover:bg-orange-700 rounded-lg text-white font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                Désactiver l'école
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('admin.schools.resend-access', $school->id) }}" 
                              onsubmit="return confirm('Renvoyer les accès par email ? Un nouveau mot de passe sera généré.')">
                            @csrf
                            <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-white font-medium transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Renvoyer les accès
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Informations générales -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Informations générales</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Nom de l'école</label>
                    <p class="text-white text-lg">{{ $school->name }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Sous-domaine</label>
                    <p class="text-white">{{ $school->slug }}.site.test</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                    <p class="text-white">{{ $school->email }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Téléphone</label>
                    <p class="text-white">{{ $school->phone ?? 'Non renseigné' }}</p>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-1">Adresse</label>
                    <p class="text-white">{{ $school->address ?? 'Non renseignée' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Date de création</label>
                    <p class="text-white">{{ $school->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-1">Base de données</label>
                    <p class="text-white font-mono text-sm">{{ $school->database }}</p>
                </div>
            </div>
            
            @if($school->logo_path)
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-400 mb-1">Logo</label>
                <img src="{{ Storage::url($school->logo_path) }}" alt="Logo" class="h-20 w-auto">
            </div>
            @endif
        </div>
    </div>

    <!-- Abonnements -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="p-6 border-b border-gray-800 flex justify-between items-center">
            <h3 class="text-lg font-semibold text-white">Abonnements</h3>
            <a href="{{ route('admin.subscriptions.create', $school->id) }}" 
               class="px-3 py-1 bg-primary-600 hover:bg-primary-700 rounded-lg text-white text-sm transition-colors">
                Nouvel abonnement
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Plan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Début</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($school->subscriptions as $subscription)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4">
                            <span class="capitalize text-white">{{ $subscription->plan }}</span>
                        </td>
                        <td class="px-6 py-4 text-white">{{ number_format($subscription->amount, 0, ',', ' ') }} Ariary</td>
                        <td class="px-6 py-4 text-gray-300">{{ $subscription->starts_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $subscription->ends_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            @if($subscription->status === 'active')
                                <span class="px-2 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">
                                    Actif
                                </span>
                                @if($subscription->ends_at <= now()->addDays(30))
                                    <span class="ml-2 px-2 py-1 bg-yellow-600/20 text-yellow-400 rounded-full text-xs">
                                        Expire bientôt
                                    </span>
                                @endif
                            @elseif($subscription->status === 'pending')
                                <span class="px-2 py-1 bg-yellow-600/20 text-yellow-400 rounded-full text-xs">
                                    En attente
                                </span>
                            @else
                                <span class="px-2 py-1 bg-red-600/20 text-red-400 rounded-full text-xs">
                                    {{ ucfirst($subscription->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($subscription->status === 'active')
                            <form method="POST" action="{{ route('admin.subscriptions.cancel', $subscription->id) }}" 
                                  onsubmit="return confirm('Annuler cet abonnement ?')">
                                @csrf
                                @method('POST')
                                <button type="submit" class="text-red-400 hover:text-red-300 transition-colors text-sm">
                                    Annuler
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Aucun abonnement pour cette école
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Plan actuel</p>
                    <p class="text-2xl font-bold text-white mt-2 capitalize">
                        {{ $school->subscriptions->where('status', 'active')->first()->plan ?? 'Aucun' }}
                    </p>
                </div>
                <div class="w-12 h-12 bg-primary-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📋</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Statut général</p>
                    <p class="text-2xl font-bold text-white mt-2">
                        @if($school->status === 'active' && $school->subscriptions->where('status', 'active')->where('ends_at', '>', now())->count() > 0)
                            <span class="text-green-400">✅ Opérationnel</span>
                        @elseif($school->status === 'pending')
                            <span class="text-yellow-400">⏳ En attente</span>
                        @elseif($school->status === 'suspended')
                            <span class="text-red-400">⛔ Suspendu</span>
                        @else
                            <span class="text-gray-400">⚠️ Inactif</span>
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">⚡</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Prochain renouvellement</p>
                    <p class="text-2xl font-bold text-white mt-2">
                        @php
                            $activeSub = $school->subscriptions->where('status', 'active')->first();
                        @endphp
                        @if($activeSub)
                            {{ $activeSub->ends_at->format('d/m/Y') }}
                        @else
                            --
                        @endif
                    </p>
                </div>
                <div class="w-12 h-12 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">📅</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection