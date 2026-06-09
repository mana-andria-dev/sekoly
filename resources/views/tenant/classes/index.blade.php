@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des Classes</h1>
                        <p class="text-gray-400 text-sm mt-1">Gérez les classes académiques de votre établissement</p>
                    </div>
                </div>
            </div>
            
            <!-- Stats & Actions -->
            <div class="flex items-center gap-3">
                <!-- Quick Stats -->
                <div class="hidden sm:flex items-center gap-4 bg-gray-900 border border-gray-800 rounded-lg px-4 py-2">
                    <div class="text-center">
                        <div class="text-lg font-bold text-white">{{ $classes->total() }}</div>
                        <div class="text-xs text-gray-400">Classes</div>
                    </div>
                    <div class="h-6 w-px bg-gray-800"></div>
                    <div class="text-center">
                        @php
                            $totalStudents = 0;
                            foreach ($classes as $class) {
                                $totalStudents += $class->students_count ?? 0;
                            }
                        @endphp
                        <div class="text-lg font-bold text-green-500">{{ $totalStudents }}</div>
                        <div class="text-xs text-gray-400">Élèves</div>
                    </div>
                </div>
                
                <a href="{{ route('classes.create', [
                                    'tenant' => tenant()->name
                                ]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle classe
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
        <form method="GET" class="flex flex-col sm:flex-row gap-4">
            <!-- Barre de recherche -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="Rechercher une classe par nom..."
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 pl-10 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all">
                    <svg class="absolute left-3 top-2.5 w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            
            <!-- Filtre année scolaire -->
            <div>
                <select name="year" 
                        class="w-full sm:w-auto bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20">
                    <option value="">Toutes années</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ request('year') == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
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
                <a href="{{ route('classes.index', [
                                    'tenant' => tenant()->name
                                ]) }}"
                   class="px-4 py-2.5 border border-gray-700 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 font-medium transition-all">
                    Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des classes -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Nom de la classe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Année scolaire
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Élèves
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Matières
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Heures/Sem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Créée le
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($classes as $class)
                    <tr class="hover:bg-gray-850/50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-primary-600/10 rounded-lg flex items-center justify-center">
                                    <span class="text-primary-600">🏫</span>
                                </div>
                                <div>
                                    <a href="{{ route('classes.show', $class) }}" 
                                        class="font-medium text-white hover:text-primary-400 transition-colors">
                                        {{ $class->name }}
                                    </a>
                                    <div class="text-xs text-gray-500 mt-1">ID: {{ $class->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($class->year)
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600/10 text-blue-400 border border-blue-600/20">
                                    {{ $class->year->name }}
                                </span>
                                @if($class->year->is_active ?? false)
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                </span>
                                @endif
                            </div>
                            @else
                            <span class="text-gray-500 text-sm italic">Non spécifiée</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-600/10 text-green-400 border border-green-600/20">
                                    {{ $class->students_count ?? 0 }} élève(s)
                                </span>
                                @if(($class->students_count ?? 0) > 0)
                                <a href="{{ route('classes.show', [
                                            'class' => $class->id
                                        ]) }}#students"
                                   class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
                                    Voir
                                </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-600/10 text-purple-400 border border-purple-600/20">
                                    {{ $class->assignments_count ?? 0 }} matière(s)
                                </span>
                                @if(($class->assignments_count ?? 0) > 0)
                                <a href="{{ route('classes.show', [
                                            'class' => $class->id
                                        ]) }}#assignments"
                                   class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
                                    Voir
                                </a>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $totalHours = $class->assignments()->sum('hours_per_week');
                            @endphp
                            @if($totalHours > 0)
                            <div class="flex items-center gap-2">
                                <span class="text-gray-300 font-medium">{{ $totalHours }}</span>
                                <span class="text-gray-500 text-sm">h/sem</span>
                            </div>
                            @else
                            <span class="text-gray-500 text-sm italic">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-300">{{ $class->created_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $class->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('classes.show', $class) }}" 
                                        class="font-medium text-white hover:text-primary-400 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('classes.edit', [
                                            'tenant' => tenant()->name,
                                            'class' => $class->id
                                        ]) }}"
                                   class="p-2 text-gray-400 hover:text-yellow-400 hover:bg-gray-800 rounded-lg transition-colors duration-200"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('classes.destroy', [
                                            'tenant' => tenant()->name,
                                            'class' => $class->id
                                        ]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ? Cette action est irréversible.')"
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
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m 1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-300 mb-2">Aucune classe trouvée</p>
                                <p class="text-sm text-gray-500 mb-4">
                                    @if(request()->anyFilled(['search', 'year']))
                                        Essayez de modifier vos critères de recherche
                                    @else
                                        Commencez par créer votre première classe
                                    @endif
                                </p>
                                <a href="{{ route('classes.create', [
                                            'tenant' => tenant()->name,
                                        ]) }}"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Créer une classe
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($classes->hasPages())
        <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50">
            {{ $classes->links() }}
        </div>
        @endif
    </div>

    <!-- Sidebar Stats (mobile) -->
    <div class="mt-6 sm:hidden">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Statistiques</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-white">{{ $classes->total() }}</div>
                    <div class="text-xs text-gray-400">Classes</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    <div class="text-lg font-bold text-green-500">{{ $totalStudents }}</div>
                    <div class="text-xs text-gray-400">Élèves</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    @php
                        $classesWithStudents = $classes->filter(function($class) {
                            return ($class->students_count ?? 0) > 0;
                        })->count();
                    @endphp
                    <div class="text-lg font-bold text-blue-500">{{ $classesWithStudents }}</div>
                    <div class="text-xs text-gray-400">Classes avec élèves</div>
                </div>
                <div class="text-center p-3 bg-gray-850/50 rounded-lg">
                    @php
                        $classesWithSubjects = $classes->filter(function($class) {
                            return ($class->assignments_count ?? 0) > 0;
                        })->count();
                    @endphp
                    <div class="text-lg font-bold text-purple-500">{{ $classesWithSubjects }}</div>
                    <div class="text-xs text-gray-400">Avec matières</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-submit des filtres année
    document.querySelectorAll('select[name="year"]').forEach(select => {
        select.addEventListener('change', function() {
            if (this.value !== '') {
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush