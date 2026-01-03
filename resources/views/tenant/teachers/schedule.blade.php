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
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Emploi du temps</h1>
                        <p class="text-gray-400 text-sm mt-1">{{ $teacher->full_name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour au profil
                </a>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="text-2xl font-bold text-white">{{ $weeklyWorkload }}h</div>
            <div class="text-sm text-gray-400">Charge hebdomadaire</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="text-2xl font-bold text-green-400">{{ $teacher->classes->count() }}</div>
            <div class="text-sm text-gray-400">Classes</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="text-2xl font-bold text-blue-400">{{ $teacher->assignments->count() }}</div>
            <div class="text-sm text-gray-400">Affectations</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="text-2xl font-bold text-purple-400">{{ $teacher->availabilities->count() }}</div>
            <div class="text-sm text-gray-400">Disponibilités</div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Disponibilités -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Disponibilités</h3>
                
                @if($teacher->availabilities->count() > 0)
                <div class="space-y-3">
                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                        @php
                            $dayAvailabilities = $teacher->availabilities->where('day_of_week', $day);
                            $dayNames = [
                                'monday' => 'Lundi',
                                'tuesday' => 'Mardi',
                                'wednesday' => 'Mercredi',
                                'thursday' => 'Jeudi',
                                'friday' => 'Vendredi',
                                'saturday' => 'Samedi',
                            ];
                        @endphp
                        @if($dayAvailabilities->count() > 0)
                        <div class="p-3 bg-gray-800/30 rounded-lg">
                            <div class="text-sm font-medium text-gray-300 mb-2">{{ $dayNames[$day] }}</div>
                            <div class="space-y-1">
                                @foreach($dayAvailabilities as $availability)
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-400">
                                        {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                        {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                    </span>
                                    @if($availability->is_recurring)
                                    <span class="text-xs text-green-400">Récurrent</span>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @else
                <div class="text-center py-6">
                    <p class="text-sm text-gray-500">Aucune disponibilité enregistrée</p>
                </div>
                @endif
                
                <div class="mt-6 pt-6 border-t border-gray-800">
                    <button type="button" 
                            onclick="document.getElementById('add-availability-form').classList.toggle('hidden')"
                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter une disponibilité
                    </button>
                    
                    <!-- Formulaire d'ajout -->
                    <div id="add-availability-form" class="hidden mt-4 p-4 bg-gray-800/50 rounded-lg">
                        <form action="{{ route('teachers.availabilities.store', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}" 
                              method="POST" class="space-y-3">
                            @csrf
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-400 mb-1">Jour</label>
                                    <select name="day_of_week" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                                        <option value="monday">Lundi</option>
                                        <option value="tuesday">Mardi</option>
                                        <option value="wednesday">Mercredi</option>
                                        <option value="thursday">Jeudi</option>
                                        <option value="friday">Vendredi</option>
                                        <option value="saturday">Samedi</option>
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-gray-400 mb-1">Heure début</label>
                                        <input type="time" name="start_time" 
                                               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-gray-400 mb-1">Heure fin</label>
                                        <input type="time" name="end_time" 
                                               class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <label class="flex items-center gap-2 text-sm text-gray-400">
                                        <input type="checkbox" name="is_recurring" value="1" checked class="rounded">
                                        Récurrent
                                    </label>
                                </div>
                            </div>
                            <div class="flex gap-2 pt-2">
                                <button type="button" 
                                        onclick="document.getElementById('add-availability-form').classList.add('hidden')"
                                        class="flex-1 px-3 py-2 text-sm bg-gray-700 hover:bg-gray-600 rounded text-gray-300">
                                    Annuler
                                </button>
                                <button type="submit" 
                                        class="flex-1 px-3 py-2 text-sm bg-primary-600 hover:bg-primary-700 rounded text-white">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Emploi du temps -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h3 class="text-lg font-semibold text-white">Planning hebdomadaire</h3>
                </div>
                <div class="p-6">
                    <!-- Sélecteur de semaine -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                </svg>
                            </button>
                            <div class="text-lg font-semibold text-white">
                                Semaine du 01 Jan
                            </div>
                            <button class="p-2 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </button>
                        </div>
                        <div class="flex items-center gap-2">
                            <button class="px-3 py-1.5 text-sm bg-gray-800 hover:bg-gray-700 rounded text-gray-300">
                                Aujourd'hui
                            </button>
                            <button class="px-3 py-1.5 text-sm bg-primary-600 hover:bg-primary-700 rounded text-white">
                                + Nouveau cours
                            </button>
                        </div>
                    </div>

                    <!-- Tableau emploi du temps -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-850">
                                    <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider border-b border-gray-800">
                                        Heure
                                    </th>
                                    @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $day)
                                    <th class="p-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider border-b border-gray-800">
                                        {{ $day }}
                                    </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @for($hour = 8; $hour <= 18; $hour++)
                                <tr class="border-b border-gray-800">
                                    <td class="p-3 text-sm text-gray-400 bg-gray-850/50">
                                        {{ sprintf('%02d:00', $hour) }}
                                    </td>
                                    @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                                    <td class="p-3 border border-gray-800 min-w-[150px] h-16">
                                        <!-- Ici tu peux ajouter la logique pour afficher les cours -->
                                        @if($hour == 10 && $day == 'monday')
                                        <div class="p-2 bg-primary-600/10 border border-primary-600/20 rounded text-xs h-full">
                                            <div class="font-medium text-white">Mathématiques</div>
                                            <div class="text-gray-400 text-xs">6ème A</div>
                                            <div class="text-gray-500 text-xs">Salle 201</div>
                                        </div>
                                        @endif
                                    </td>
                                    @endforeach
                                </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <!-- Légende -->
                    <div class="mt-6 pt-6 border-t border-gray-800">
                        <div class="text-sm font-medium text-gray-300 mb-3">Légende</div>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-primary-600/20 border border-primary-600/40 rounded"></div>
                                <span class="text-xs text-gray-400">Cours</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-green-600/20 border border-green-600/40 rounded"></div>
                                <span class="text-xs text-gray-400">Disponible</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 bg-gray-600/20 border border-gray-600/40 rounded"></div>
                                <span class="text-xs text-gray-400">Indisponible</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Affectations -->
    <div class="mt-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Affectations actuelles</h3>
            
            @if($teacher->assignments->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($teacher->assignments as $assignment)
                <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="font-medium text-white">{{ $assignment->subject->name ?? 'N/A' }}</div>
                            <div class="text-xs text-gray-500">{{ $assignment->subject->code ?? '' }}</div>
                        </div>
                        <span class="px-2 py-1 text-xs bg-primary-600/10 text-primary-400 rounded">
                            {{ $assignment->hours_per_week }}h/sem
                        </span>
                    </div>
                    <div class="space-y-2">
                        <div class="text-sm text-gray-300">
                            {{ $assignment->schoolClass->name ?? 'N/A' }}
                        </div>
                        @if($assignment->coefficient)
                        <div class="text-xs text-gray-500">
                            Coefficient: {{ $assignment->coefficient }}
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6">
                <p class="text-sm text-gray-500">Aucune affectation actuelle</p>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection