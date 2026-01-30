@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary-600/10 rounded-lg">
                        <span class="text-xl text-primary-600">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Emploi du temps - {{ $class->name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">
                            @if($timetable)
                            {{ $timetable->name }} • {{ $timetable->academicYear->name ?? 'N/A' }}
                            @else
                            Aucun emploi du temps actif
                            @endif
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($timetable)
                <button onclick="window.print()"
                        class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Imprimer
                </button>
                
                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Gérer les créneaux
                </a>
                <a href="{{ route('timetables.create', ['tenant' => app('tenant')->name]) }}?class_id={{ $class->id }}"
                   class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Créer un emploi du temps
                </a>
                @endif
            </div>
        </div>
    </div>

    @if($timetable)
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">{{ $slotsByDay->flatten()->count() }}</div>
                    <div class="text-sm text-gray-400">Cours/semaine</div>
                </div>
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <span class="text-lg text-blue-500">📚</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    @php
                        $totalHours = $slotsByDay->flatten()->sum(function($slot) {
                            $start = strtotime($slot->start_time);
                            $end = strtotime($slot->end_time);
                            return ($end - $start) / 3600;
                        });
                    @endphp
                    <div class="text-2xl font-bold text-white">{{ number_format($totalHours, 1) }}h</div>
                    <div class="text-sm text-gray-400">Heures/semaine</div>
                </div>
                <div class="p-2 bg-green-600/10 rounded-lg">
                    <span class="text-lg text-green-500">⏰</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $slotsByDay->flatten()->pluck('teacher_id')->unique()->count() }}
                    </div>
                    <div class="text-sm text-gray-400">Professeurs</div>
                </div>
                <div class="p-2 bg-purple-600/10 rounded-lg">
                    <span class="text-lg text-purple-500">👨‍🏫</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-2xl font-bold text-white">
                        {{ $slotsByDay->flatten()->pluck('subject_id')->unique()->count() }}
                    </div>
                    <div class="text-sm text-gray-400">Matières</div>
                </div>
                <div class="p-2 bg-orange-600/10 rounded-lg">
                    <span class="text-lg text-orange-500">📖</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Emploi du temps visuel -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-800">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h3 class="text-lg font-semibold text-white">Emploi du temps hebdomadaire</h3>
                <div class="text-sm text-gray-400">
                    Valide du {{ $timetable->start_date->format('d/m/Y') }} au {{ $timetable->end_date->format('d/m/Y') }}
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400 uppercase tracking-wider min-w-32">
                            Heure / Jour
                        </th>
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $dayIndex => $dayName)
                        @php
                            $dayNumber = $dayIndex + 1;
                            $daySlots = $slotsByDay[$dayNumber] ?? collect();
                            $dayHours = $daySlots->sum(function($slot) {
                                $start = strtotime($slot->start_time);
                                $end = strtotime($slot->end_time);
                                return ($end - $start) / 3600;
                            });
                        @endphp
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-400 uppercase tracking-wider min-w-48">
                            <div>{{ $dayName }}</div>
                            <div class="text-xs text-gray-500">{{ number_format($dayHours, 1) }}h</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
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
                        
                        @for($day = 1; $day <= 5; $day++)
                        @php
                            $slot = $slotsByDay[$day] ?? collect();
                            $currentSlot = $slot->first(function($s) use ($timeSlot) {
                                return $s->start_time->format('H:i') == $timeSlot[0];
                            });
                        @endphp
                        
                        <td class="px-4 py-3 border-l border-gray-800 min-h-16">
                            @if($currentSlot)
                            <div class="p-2 rounded-lg cursor-pointer hover:opacity-90 transition-opacity"
                                 style="background-color: {{ $currentSlot->color }}20; border-left: 3px solid {{ $currentSlot->color }}"
                                 title="{{ $currentSlot->subject->name }} - {{ $currentSlot->teacherProfile->full_name ?? 'Professeur non défini' }}">
                                <div class="font-medium text-white text-sm flex items-center justify-between">
                                    <span>{{ $currentSlot->subject->code }}</span>
                                    @if($currentSlot->classroom)
                                    <span class="text-xs text-gray-300">🏫{{ $currentSlot->classroom->code ?? $currentSlot->classroom->name }}</span>
                                    @endif
                                </div>
                                <div class="text-xs text-gray-300 truncate">{{ $currentSlot->subject->name }}</div>
                                @if($currentSlot->teacherProfile)
                                <div class="text-xs text-gray-400 flex items-center gap-1 truncate">
                                    👨‍🏫 {{ $currentSlot->teacherProfile->first_name }}
                                </div>
                                @elseif($currentSlot->teacher)
                                <div class="text-xs text-gray-400 truncate">
                                    👨‍🏫 {{ $currentSlot->teacher->name }}
                                </div>
                                @endif
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

    <!-- Détails par jour -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Liste des cours par jour -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Détails des cours</h3>
                </div>
                
                <div class="divide-y divide-gray-800">
                    @foreach($slotsByDay as $dayNumber => $daySlots)
                    @if($daySlots->isNotEmpty())
                    @php
                        $dayNames = ['', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                        $dayName = $dayNames[$dayNumber] ?? "Jour $dayNumber";
                        $dayHours = $daySlots->sum(function($slot) {
                            $start = strtotime($slot->start_time);
                            $end = strtotime($slot->end_time);
                            return ($end - $start) / 3600;
                        });
                    @endphp
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-md font-semibold text-white">{{ $dayName }}</h4>
                            <span class="text-sm text-gray-400">{{ number_format($dayHours, 1) }}h de cours</span>
                        </div>
                        
                        <div class="space-y-3">
                            @foreach($daySlots->sortBy('start_time') as $slot)
                            <div class="p-3 bg-gray-800/50 rounded-lg"
                                 style="border-left: 4px solid {{ $slot->color }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="font-medium text-white">{{ $slot->subject->name }}</span>
                                            <span class="text-xs px-2 py-1 bg-gray-700 rounded">{{ $slot->subject->code }}</span>
                                            <span class="text-sm text-gray-400">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                            <div>
                                                <span class="text-gray-400">Professeur:</span>
                                                <span class="text-white ml-2">
                                                    {{ $slot->teacherProfile->full_name ?? $slot->teacher->name ?? 'Non attribué' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Salle:</span>
                                                <span class="text-white ml-2">
                                                    {{ $slot->classroom->name ?? 'Non attribuée' }}
                                                </span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Durée:</span>
                                                <span class="text-white ml-2">{{ $slot->duration }}h</span>
                                            </div>
                                            <div>
                                                <span class="text-gray-400">Type:</span>
                                                <span class="text-white ml-2">
                                                    {{ $slot->recurring ? 'Récurrent' : 'Ponctuel' }}
                                                </span>
                                            </div>
                                        </div>
                                        
                                        @if($slot->notes)
                                        <div class="mt-2 pt-2 border-t border-gray-700">
                                            <div class="text-xs text-gray-500">{{ $slot->notes }}</div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Résumé et informations -->
        <div>
            <!-- Professeurs de la classe -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">Professeurs</h3>
                
                <div class="space-y-3">
                    @php
                        $teachers = $slotsByDay->flatten()
                            ->map(function($slot) {
                                return [
                                    'id' => $slot->teacher_id,
                                    'name' => $slot->teacherProfile->full_name ?? $slot->teacher->name ?? null,
                                    'subject' => $slot->subject->name,
                                    'hours' => $slot->duration
                                ];
                            })
                            ->filter(function($teacher) {
                                return !empty($teacher['name']);
                            })
                            ->groupBy('id')
                            ->map(function($group) {
                                return [
                                    'name' => $group->first()['name'],
                                    'subjects' => $group->pluck('subject')->unique()->implode(', '),
                                    'total_hours' => $group->sum('hours')
                                ];
                            });
                    @endphp
                    
                    @foreach($teachers as $teacher)
                    <div class="p-3 bg-gray-800/50 rounded-lg">
                        <div class="font-medium text-white">{{ $teacher['name'] }}</div>
                        <div class="text-sm text-gray-400 mt-1">{{ $teacher['subjects'] }}</div>
                        <div class="text-xs text-gray-500 mt-2">
                            {{ number_format($teacher['total_hours'], 1) }}h/semaine
                        </div>
                    </div>
                    @endforeach
                    
                    @if($teachers->isEmpty())
                    <div class="text-center py-4">
                        <span class="text-gray-600 text-sm">Aucun professeur attribué</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <!-- Heures par matière -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">Répartition par matière</h3>
                
                <div class="space-y-3">
                    @php
                        $subjectStats = $slotsByDay->flatten()
                            ->groupBy('subject_id')
                            ->map(function($slots, $subjectId) {
                                $subject = $slots->first()->subject;
                                return [
                                    'name' => $subject->name,
                                    'code' => $subject->code,
                                    'hours' => $slots->sum('duration'),
                                    'count' => $slots->count(),
                                    'color' => $slots->first()->color
                                ];
                            })
                            ->sortByDesc('hours');
                    @endphp
                    
                    @foreach($subjectStats as $stat)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 rounded-full" style="background-color: {{ $stat['color'] }}"></div>
                            <div>
                                <div class="text-sm text-gray-300">{{ $stat['code'] }}</div>
                                <div class="text-xs text-gray-500 truncate" style="max-width: 120px;">{{ $stat['name'] }}</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-white">{{ number_format($stat['hours'], 1) }}h</div>
                            <div class="text-xs text-gray-500">{{ $stat['count'] }} cours</div>
                        </div>
                    </div>
                    @endforeach
                    
                    <!-- Total -->
                    <div class="pt-3 border-t border-gray-800 mt-3">
                        <div class="flex items-center justify-between font-medium">
                            <span class="text-white">Total</span>
                            <span class="text-primary-400">{{ number_format($totalHours, 1) }}h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Actions</h3>
                
                <div class="space-y-3">
                    <button onclick="window.print()"
                            class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        📄 Imprimer l'emploi du temps
                    </button>
                    
                    <a href="{{ route('timetables.show', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                       class="block w-full text-center px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        Gérer l'emploi du temps
                    </a>
                    
                    <a href="{{ route('classes.show', ['tenant' => app('tenant')->name, 'schoolClass' => $class->id]) }}"
                       class="block w-full text-center px-4 py-2.5 bg-primary-600/10 hover:bg-primary-600/20 text-primary-400 rounded-lg text-sm font-medium transition-all duration-200">
                        Retour à la fiche de la classe
                    </a>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Aucun emploi du temps -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center">
        <div class="text-gray-500">
            <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-gray-300 mt-4">Aucun emploi du temps actif</h3>
        <p class="text-sm text-gray-500 mt-2">
            Cette classe n'a pas d'emploi du temps actif pour le moment.
        </p>
        <div class="mt-6 flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('timetables.create', ['tenant' => app('tenant')->name]) }}?class_id={{ $class->id }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer un emploi du temps
            </a>
            <a href="{{ route('classes.show', ['tenant' => app('tenant')->name, 'class' => $class->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                Retour à la classe
            </a>
        </div>
    </div>
    @endif
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
        font-size: 12px !important;
    }
    
    .bg-gray-900 {
        background: white !important;
        border: 1px solid #ddd !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .text-gray-400 {
        color: #666 !important;
    }
    
    .border-gray-800 {
        border-color: #ddd !important;
    }
    
    .bg-gray-800 {
        background: #f5f5f5 !important;
    }
    
    table {
        font-size: 10px !important;
    }
    
    .p-6 {
        padding: 12px !important;
    }
    
    .mb-8 {
        margin-bottom: 16px !important;
    }
    
    .gap-6 {
        gap: 12px !important;
    }
}
</style>
@endsection