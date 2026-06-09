@extends('layouts.admin')

@section('title', 'Gestion des écoles')
@section('subtitle', 'Liste de toutes les écoles')

@section('content')
<div class="space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <!-- Search form -->
            <form method="GET" class="flex gap-2">
                <input type="text" name="search" placeholder="Rechercher une école..." 
                       class="bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary-600"
                       value="{{ request('search') }}">
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                    Rechercher
                </button>
            </form>
        </div>
        <a href="{{ route('admin.schools.create') }}" 
           class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors flex items-center gap-2">
            <span>➕</span> Nouvelle école
        </a>
    </div>
    
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">École</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Abonnement</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date création</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($schools as $school)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-info rounded-lg flex items-center justify-center">
                                    <span class="text-white font-bold">{{ substr($school->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ $school->name }}</div>
                                    <div class="text-sm text-gray-400">{{ $school->slug }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-300">{{ $school->email }}</div>
                            <div class="text-sm text-gray-400">{{ $school->phone ?? 'Pas de téléphone' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($school->subscriptions->isNotEmpty())
                                @php $subscription = $school->subscriptions->first() @endphp
                                <div class="space-y-1">
                                    <div class="text-gray-300">{{ ucfirst($subscription->plan) }}</div>
                                    <div class="text-xs text-gray-400">
                                        Expire: {{ \Carbon\Carbon::parse($subscription->ends_at)->format('d/m/Y') }}
                                    </div>
                                </div>
                            @else
                                <span class="text-yellow-400 text-sm">Aucun abonnement</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($school->subscriptions->isNotEmpty() && $school->subscriptions->first()->status === 'active')
                                <span class="px-2 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Actif</span>
                            @else
                                <span class="px-2 py-1 bg-gray-600/20 text-gray-400 rounded-full text-xs">Inactif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $school->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.schools.show', $school->id) }}" 
                                   class="text-blue-400 hover:text-blue-300 transition-colors">
                                    Voir
                                </a>
                                <a href="{{ route('admin.schools.edit', $school->id) }}" 
                                   class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                    Modifier
                                </a>
                                <form method="POST" action="{{ route('admin.schools.destroy', $school->id) }}" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette école ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Aucune école trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-4">
        {{ $schools->links() }}
    </div>
</div>
@endsection