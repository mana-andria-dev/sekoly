@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header avec statistiques -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary-600/10 rounded-lg">
                        <span class="text-xl text-primary-600">👨‍🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des Professeurs</h1>
                        <p class="text-gray-400 text-sm mt-1">Enseignants de l'établissement</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Filtres -->
                <div class="flex gap-2">
                    <form method="GET" class="flex gap-2">
                        <input type="text" name="search" placeholder="Rechercher..." 
                               value="{{ request('search') }}"
                               class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white w-48">
                        <select name="status" class="bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-sm text-white">
                            <option value="">Tous les statuts</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                            <option value="on_leave" {{ request('status') == 'on_leave' ? 'selected' : '' }}>En congé</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                        </select>
                        <button type="submit" class="px-3 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm">
                            🔍
                        </button>
                    </form>
                </div>
                
                <a href="{{ route('teachers.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouveau professeur
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        @php
            $total = $teachers->total();
            $active = $teachers->where('status', 'active')->count();
            $onLeave = $teachers->where('status', 'on_leave')->count();
            $vacataires = $teachers->where('employment_type', 'Vacataire')->count();
        @endphp
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $total }}</div>
                    <div class="text-sm text-gray-400">Total professeurs</div>
                </div>
                <div class="p-2 bg-gray-800 rounded-lg">
                    <span class="text-lg">👥</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-green-400">{{ $active }}</div>
                    <div class="text-sm text-gray-400">Actifs</div>
                </div>
                <div class="p-2 bg-green-600/10 rounded-lg">
                    <span class="text-lg text-green-500">✅</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-blue-400">{{ $onLeave }}</div>
                    <div class="text-sm text-gray-400">En congé</div>
                </div>
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <span class="text-lg text-blue-500">🏖️</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-orange-400">{{ $vacataires }}</div>
                    <div class="text-sm text-gray-400">Vacataires</div>
                </div>
                <div class="p-2 bg-orange-600/10 rounded-lg">
                    <span class="text-lg text-orange-500">⏱️</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tableau des professeurs -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Professeur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Informations
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Matières
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
                    @forelse($teachers as $teacher)
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-primary-600/10 rounded-full flex items-center justify-center">
                                    @if($teacher->photo)
                                    <img src="{{ Storage::url($teacher->photo) }}" alt="{{ $teacher->full_name }}" 
                                         class="w-12 h-12 rounded-full object-cover">
                                    @else
                                    <span class="text-primary-600 text-xl">👨‍🏫</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $teacher->full_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $teacher->teacher_id }}</div>
                                    <div class="text-xs text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                        </svg>
                                        {{ $teacher->email }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-300">
                                    {{ $teacher->academic_degree }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $teacher->specialization }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    <span class="text-gray-400">Embauché:</span> 
                                    {{ $teacher->hire_date->format('d/m/Y') }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($teacher->subjects->take(3) as $subject)
                                <span class="px-2 py-1 text-xs bg-gray-800 text-gray-300 rounded">
                                    {{ $subject->code }}
                                </span>
                                @endforeach
                                @if($teacher->subjects->count() > 3)
                                <span class="px-2 py-1 text-xs bg-gray-800 text-gray-400 rounded">
                                    +{{ $teacher->subjects->count() - 3 }}
                                </span>
                                @endif
                            </div>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $teacher->subjects->count() }} matière(s)
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-600/10 text-green-400 border-green-600/20',
                                    'on_leave' => 'bg-blue-600/10 text-blue-400 border-blue-600/20',
                                    'inactive' => 'bg-gray-600/10 text-gray-400 border-gray-600/20',
                                    'retired' => 'bg-orange-600/10 text-orange-400 border-orange-600/20',
                                ];
                                $typeColors = [
                                    'CDI' => 'bg-purple-600/10 text-purple-400 border-purple-600/20',
                                    'CDD' => 'bg-yellow-600/10 text-yellow-400 border-yellow-600/20',
                                    'Vacataire' => 'bg-pink-600/10 text-pink-400 border-pink-600/20',
                                    'Contractuel' => 'bg-indigo-600/10 text-indigo-400 border-indigo-600/20',
                                ];
                            @endphp
                            <div class="space-y-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$teacher->status] }} border">
                                    {{ ucfirst($teacher->status) }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $typeColors[$teacher->employment_type] ?? 'bg-gray-600/10 text-gray-400' }} border">
                                    {{ $teacher->employment_type }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('teachers.show', ['teacher' => $teacher->id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('teachers.edit', ['teacher' => $teacher->id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                {{-- 
                                <a href="{{ route('teachers.schedule', ['teacher' => $teacher->id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Emploi du temps">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </a>
                                --}}
                                
                                <form action="{{ route('teachers.destroy', ['teacher' => $teacher->id]) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Archiver ce professeur ?')"
                                            class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors"
                                            title="Archiver">
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
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-300 mt-4">Aucun professeur trouvé</h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Commencez par ajouter vos premiers professeurs.
                            </p>
                            <a href="{{ route('teachers.create') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 mt-4 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Ajouter un professeur
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($teachers->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
</div>
@endsection