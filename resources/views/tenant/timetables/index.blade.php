@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary-600/10 rounded-lg">
                        <span class="text-xl text-primary-600">📅</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Emplois du temps</h1>
                        <p class="text-gray-400 text-sm mt-1">Gestion des horaires des classes</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('timetables.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvel emploi du temps
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <a href="{{ route('classes.index') }}?filter=timetable"
           class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <span class="text-lg text-blue-500">🏫</span>
                </div>
                <div>
                    <div class="font-medium text-white">Par classe</div>
                    <div class="text-sm text-gray-400">Voir les emplois du temps par classe</div>
                </div>
            </div>
        </a>
        
        <a href="{{ route('teachers.index') }}?filter=schedule"
           class="bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-green-600/10 rounded-lg">
                    <span class="text-lg text-green-500">👨‍🏫</span>
                </div>
                <div>
                    <div class="font-medium text-white">Par professeur</div>
                    <div class="text-sm text-gray-400">Voir les horaires des enseignants</div>
                </div>
            </div>
        </a>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-purple-600/10 rounded-lg">
                        <span class="text-lg text-purple-500">📊</span>
                    </div>
                    <div>
                        <div class="font-medium text-white">Statistiques</div>
                        <div class="text-sm text-gray-400">{{ $timetables->total() }} emploi(s) du temps</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste des emplois du temps -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Emploi du temps
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Classe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Période
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
                    @forelse($timetables as $timetable)
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-gradient-to-br from-primary-600/20 to-primary-600/5 rounded-lg flex items-center justify-center">
                                    <span class="text-primary-600 text-xl">📅</span>
                                </div>
                                <div>
                                    <div class="font-semibold text-white">{{ $timetable->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $timetable->description ?: 'Aucune description' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <span class="px-3 py-1 bg-gray-800 text-gray-300 rounded-full text-sm">
                                    @if($timetable->class)
                                        {{ $timetable->class->name }}
                                    @else
                                        <span class="text-red-400">Classe supprimée</span>
                                    @endif
                                </span>
                                <span class="text-xs text-gray-500">
                                    {{ $timetable->class->students_count ?? 0 }} élèves
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-300">
                                    Du {{ $timetable->start_date->format('d/m/Y') }}
                                </div>
                                <div class="text-sm text-gray-300">
                                    Au {{ $timetable->end_date->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $timetable->academicYear->name }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                @if($timetable->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-600/10 text-green-400 border border-green-600/20">
                                    <span class="w-2 h-2 bg-green-400 rounded-full mr-2"></span>
                                    Actif
                                </span>
                                @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-600/10 text-gray-400 border border-gray-600/20">
                                    <span class="w-2 h-2 bg-gray-400 rounded-full mr-2"></span>
                                    Inactif
                                </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('timetables.show', ['timetable' => $timetable->id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Voir">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('timetables.manage-slots', ['timetable' => $timetable->id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Gérer les créneaux">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                
                                <a href="{{ route('classes.timetable', ['class' => $timetable->class_id]) }}"
                                   class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Vue classe">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-medium text-gray-300 mt-4">Aucun emploi du temps</h3>
                            <p class="text-sm text-gray-500 mt-2">
                                Créez votre premier emploi du temps pour une classe.
                            </p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($timetables->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $timetables->links() }}
        </div>
        @endif
    </div>
</div>
@endsection