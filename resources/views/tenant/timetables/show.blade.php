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
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $timetable->name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">
                            {{ $timetable->class->name }} • {{ $timetable->academicYear->name }}
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($conflicts->isNotEmpty())
                <a href="{{ route('timetables.conflicts', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium transition-all duration-200">
                    ⚠️ {{ $conflicts->count() }} conflit(s)
                </a>
                @endif
                
                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Gérer les créneaux
                </a>
                
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        Actions
                    </button>
                    
                    <div x-show="open" @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-gray-900 border border-gray-800 rounded-lg shadow-lg z-10">
                        <a href="{{ route('timetables.print', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                           target="_blank"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                            Imprimer
                        </a>
                        <a href="{{ route('timetables.duplicate', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                            Dupliquer
                        </a>
                        <form action="{{ route('timetables.destroy', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" 
                              method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Archiver cet emploi du temps ?')"
                                    class="block w-full text-left px-4 py-2 text-sm text-red-400 hover:bg-gray-800">
                                Archiver
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $totalSlots }}</div>
                    <div class="text-sm text-gray-400">Créneaux</div>
                </div>
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <span class="text-lg text-blue-500">⏰</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $totalHours }}h</div>
                    <div class="text-sm text-gray-400">Heures/semaine</div>
                </div>
                <div class="p-2 bg-green-600/10 rounded-lg">
                    <span class="text-lg text-green-500">📊</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $subjectHours->count() }}</div>
                    <div class="text-sm text-gray-400">Matières</div>
                </div>
                <div class="p-2 bg-purple-600/10 rounded-lg">
                    <span class="text-lg text-purple-500">📚</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $timetable->is_active ? 'Actif' : 'Inactif' }}
                    </div>
                    <div class="text-sm text-gray-400">Statut</div>
                </div>
                <div class="p-2 {{ $timetable->is_active ? 'bg-green-600/10' : 'bg-gray-600/10' }} rounded-lg">
                    <span class="text-lg {{ $timetable->is_active ? 'text-green-500' : 'text-gray-500' }}">
                        {{ $timetable->is_active ? '✅' : '⏸️' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Emploi du temps visuel -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Emploi du temps hebdomadaire</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400 uppercase tracking-wider min-w-32">
                            Heure / Jour
                        </th>
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $dayIndex => $dayName)
                        @php
                            $dayNumber = $dayIndex + 1;
                            $daySlots = $slotsByDay[$dayNumber] ?? collect();
                            $dayHours = $daySlots->sum('duration');
                        @endphp
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-400 uppercase tracking-wider min-w-48">
                            <div>{{ $dayName }}</div>
                            <div class="text-xs text-gray-500 mt-1">{{ $dayHours }}h</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <!-- Tranches horaires standards -->
                    @php
                        $timeSlots = [
                            ['08:00', '09:30'],
                            ['09:45', '11:15'],
                            ['11:30', '13:00'],
                            ['14:00', '15:30'],
                            ['15:45', '17:15'],
                            ['17:30', '19:00']
                        ];
                    @endphp
                    
                    @foreach($timeSlots as $timeSlot)
                    <tr class="hover:bg-gray-850/30">
                        <td class="px-4 py-3 bg-gray-850/50">
                            <div class="text-center">
                                <div class="font-medium text-white">{{ $timeSlot[0] }} - {{ $timeSlot[1] }}</div>
                                <div class="text-xs text-gray-500">1h30</div>
                            </div>
                        </td>
                        
                        @for($day = 1; $day <= 6; $day++)
                        @php
                            $slot = $slotsByDay[$day] ?? collect();
                            $currentSlot = $slot->first(function($s) use ($timeSlot) {
                                return $s->start_time->format('H:i') == $timeSlot[0];
                            });
                        @endphp
                        
                        <td class="px-4 py-3 border-l border-gray-800 min-h-16">
                            @if($currentSlot)
                            <div class="p-2 rounded-lg cursor-pointer hover:opacity-90 transition-opacity group relative"
                                 style="background-color: {{ $currentSlot->color }}20; border-left: 3px solid {{ $currentSlot->color }}"
                                 title="{{ $currentSlot->subject->name }} - {{ $currentSlot->teacherProfile->full_name ?? 'Professeur non défini' }}">
                                <div class="font-medium text-white text-sm flex items-center justify-between">
                                    <span>{{ $currentSlot->subject->code }}</span>
                                    @if($currentSlot->classroom)
                                    <span class="text-xs text-gray-300">🏫{{ $currentSlot->classroom->code }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-300 truncate">{{ $currentSlot->subject->name }}</div>
                                @if($currentSlot->teacherProfile)
                                <div class="text-xs text-gray-400 flex items-center gap-1 truncate">
                                    👨‍🏫 {{ $currentSlot->teacherProfile->first_name }}
                                </div>
                                @endif
                                
                                <!-- Menu d'actions au survol -->
                                <div class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $currentSlot->id }}"
                                       class="p-1 text-gray-400 hover:text-white bg-gray-800 rounded">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            @else
                            <div class="text-center py-4">
                                <span class="text-gray-600 text-sm">—</span>
                            </div>
                            @endif
                        </td>
                        @endfor
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Sections d'informations -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Affectations non planifiées -->
        @if($unassignedAssignments->isNotEmpty())
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Affectations non planifiées</h3>
                
                <div class="space-y-3">
                    @foreach($unassignedAssignments as $assignment)
                    <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-1">
                                <span class="font-medium text-white">{{ $assignment->subject->name }}</span>
                                <span class="text-xs px-2 py-1 bg-gray-700 rounded">{{ $assignment->subject->code }}</span>
                                <span class="text-sm text-gray-400">{{ $assignment->hours_per_week }}h/semaine</span>
                            </div>
                            @if($assignment->teacher)
                            <div class="text-sm text-gray-400">
                                👨‍🏫 {{ $assignment->teacher->name }}
                            </div>
                            @else
                            <div class="text-sm text-yellow-400">
                                ⚠️ Aucun professeur assigné
                            </div>
                            @endif
                        </div>
                        
                        <form action="{{ route('timetables.generate-from-assignments', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" 
                              method="POST">
                            @csrf
                            <input type="hidden" name="assignment_id" value="{{ $assignment->id }}">
                            <button type="submit" 
                                    class="px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white">
                                Planifier
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Statistiques par matière -->
        <div class="{{ $unassignedAssignments->isNotEmpty() ? 'lg:col-span-1' : 'lg:col-span-3' }}">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Heures par matière</h3>
                
                <div class="space-y-3">
                    @foreach($subjectHours as $subject)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" 
                                 style="background-color: {{ $subject->color ?? '#3B82F6' }}"></div>
                            <span class="text-gray-300">{{ $subject->code }}</span>
                            <span class="text-sm text-gray-500 truncate flex-1">{{ $subject->name }}</span>
                        </div>
                        <span class="text-gray-400">{{ $subject->total_hours }}h</span>
                    </div>
                    @endforeach
                    
                    <!-- Total -->
                    <div class="pt-3 border-t border-gray-800 mt-3">
                        <div class="flex items-center justify-between font-medium">
                            <span class="text-white">Total hebdomadaire</span>
                            <span class="text-primary-400">{{ $totalHours }} heures</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection