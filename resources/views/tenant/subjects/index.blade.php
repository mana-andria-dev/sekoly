<!-- resources/views/tenant/subjects/index.blade.php -->
@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">📚</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des Matières</h1>
                        <p class="text-gray-400 text-sm mt-1">Gérez les matières académiques de votre établissement</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Stats rapides -->
                <div class="hidden sm:flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2">
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">{{ $stats['total'] }}</div>
                        <div class="text-xs text-gray-400">Total</div>
                    </div>
                    <div class="h-6 w-px bg-gray-800"></div>
                    <div class="text-center">
                        <div class="text-lg font-bold text-green-500">{{ $stats['active'] }}</div>
                        <div class="text-xs text-gray-400">Actives</div>
                    </div>
                </div>
                
                <a href="{{ route('subjects.create', [
                            'tenant' => app('tenant')->name
                        ]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle matière
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <!-- Barre de recherche -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher une matière par nom, code ou description..."
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 pl-10 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Filtre niveau -->
            <div>
                <select name="level" 
                        class="w-full sm:w-auto bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20">
                    <option value="">Tous niveaux</option>
                    @foreach($levels as $value => $label)
                        <option value="{{ $value }}" {{ request('level') == $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Filtre statut -->
            <div>
                <select name="active" 
                        class="w-full sm:w-auto bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20">
                    <option value="">Tous statuts</option>
                    <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Actives</option>
                    <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactives</option>
                </select>
            </div>
            
            <!-- Boutons -->
            <div class="flex gap-2">
                <button type="submit" 
                        class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-all">
                    <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                <a href="/subjects/"
                   class="px-4 py-2.5 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 font-medium transition-all">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Code
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Nom
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Niveau
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Heures/Sem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Coef.
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Affectations
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($subjects as $subject)
                    <tr class="hover:bg-gray-850/50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="font-mono text-sm font-medium text-gray-300">{{ $subject->code ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $subject->name }}</div>
                            @if($subject->description)
                            <div class="text-sm text-gray-400 truncate max-w-xs mt-1">{{ Str::limit($subject->description, 60) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($subject->level)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600/10 text-blue-400 border border-blue-600/20">
                                {{ $subject->level_label }}
                            </span>
                            @else
                            <span class="text-gray-500 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="text-gray-300 font-medium">{{ $subject->hours_per_week }}</span>
                                <span class="text-gray-500 text-sm">h/sem</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-600/10 text-purple-400 border border-purple-600/20">
                                {{ $subject->formatted_coefficient }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-xs text-gray-500">{{ $subject->teachers_count }} prof(s)</span>
                                <span class="text-xs text-gray-500">{{ $subject->classes_count }} classe(s)</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('subjects.toggle-active', [
                                    'tenant' => app('tenant')->name,
                                    'subject' => $subject->id
                                ]) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                        onclick="return confirm('Changer le statut de cette matière ?')"
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 {{ $subject->is_active ? 'bg-green-600' : 'bg-gray-700' }}">
                                    <span class="sr-only">Toggle status</span>
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $subject->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                            <span class="ml-2 text-sm {{ $subject->is_active ? 'text-green-400' : 'text-gray-400' }}">
                                {{ $subject->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('subjects.show', [
                                        'tenant' => app('tenant')->name,
                                        'subject' => $subject->id
                                    ]) }}"
                                   class="p-2 text-gray-400 hover:text-blue-400 hover:bg-gray-800 rounded-lg transition-colors duration-200"
                                   title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('subjects.edit', [
                                        'tenant' => app('tenant')->name,
                                        'subject' => $subject->id
                                    ]) }}"
                                       class="p-2 text-gray-400 hover:text-yellow-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('subjects.destroy', [
                                        'tenant' => app('tenant')->name,
                                        'subject' => $subject->id
                                    ]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ? Cette action est irréversible.')"
                                            class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors duration-200"
                                            title="Supprimer">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l9-5-9-5-9 5 9 5z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-300 mb-2">Aucune matière trouvée</p>
                                <p class="text-sm text-gray-500 mb-4">
                                    @if(request()->anyFilled(['search', 'level', 'active']))
                                        Essayez de modifier vos critères de recherche
                                    @else
                                        Commencez par créer votre première matière
                                    @endif
                                </p>
                                <a href="/subjects/create"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Créer une matière
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($subjects->hasPages())
        <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50">
            {{ $subjects->links() }}
        </div>
        @endif
    </div>

    <!-- Sidebar Stats (mobile) -->
    <div class="mt-6 sm:hidden">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Statistiques</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-white">{{ $stats['total'] }}</div>
                    <div class="text-xs text-gray-400">Total</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-green-500">{{ $stats['active'] }}</div>
                    <div class="text-xs text-gray-400">Actives</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-blue-500">{{ $stats['primaire'] }}</div>
                    <div class="text-xs text-gray-400">Primaire</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-purple-500">{{ $stats['college'] }}</div>
                    <div class="text-xs text-gray-400">Collège</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit des filtres statut
    document.querySelectorAll('select[name="active"]').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value !== '') {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush