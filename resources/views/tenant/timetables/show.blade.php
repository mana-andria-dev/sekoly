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
                <a href="{{ route('timetables.conflicts', ['timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium transition-all duration-200">
                    ⚠️ {{ $conflicts->count() }} conflit(s)
                </a>
                @endif
                
                <a href="{{ route('timetables.manage-slots', ['timetable' => $timetable->id]) }}"
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
                        <a href="{{ route('timetables.print', ['timetable' => $timetable->id]) }}"
                           target="_blank"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                            Imprimer
                        </a>
                        <a href="{{ route('timetables.duplicate', ['timetable' => $timetable->id]) }}"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-800">
                            Dupliquer
                        </a>
                        <form action="{{ route('timetables.destroy', ['timetable' => $timetable->id]) }}" 
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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
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

    <!-- Emploi du temps visuel avec créneaux fixes de 1h -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden mb-6">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Emploi du temps hebdomadaire</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-400 uppercase tracking-wider min-w-40">
                            Heure
                        </th>
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $dayIndex => $dayName)
                        @php
                            $dayNumber = $dayIndex + 1;
                            $daySlots = $slotsByDay[$dayNumber] ?? collect();
                            $dayHours = $daySlots->sum('duration');
                            
                            // Vérifier s'il y a des conflits pour ce jour
                            $dayHasConflicts = $conflicts->where('day_of_week', $dayNumber)->isNotEmpty();
                        @endphp
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-400 uppercase tracking-wider min-w-48
                            {{ $dayHasConflicts ? 'bg-red-900/20 border-b-2 border-red-500' : '' }}">
                            <div class="flex items-center justify-center gap-2">
                                {{ $dayName }}
                                @if($dayHasConflicts)
                                <span class="text-red-400" title="Conflits détectés">⚠️</span>
                                @endif
                            </div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @for($hour = $startHour; $hour < $endHour; $hour++)
                    @php
                        $startTime = sprintf('%02d:00', $hour);
                        $endTime = sprintf('%02d:00', $hour + 1);
                        $displayStart = sprintf('%dh', $hour);
                        $displayEnd = sprintf('%dh', $hour + 1);
                    @endphp
                    <tr class="hover:bg-gray-850/30">
                        <!-- COLONNE HEURE -->
                        <td class="px-4 py-3 bg-gray-850/50">
                            <div class="text-center">
                                <div class="font-medium text-white text-sm">
                                    {{ $displayStart }} - {{ $displayEnd }}
                                </div>
                            </div>
                        </td>
                        
                        @for($day = 1; $day <= 6; $day++)
                        @php
                            $daySlots = $slotsByDay[$day] ?? collect();
                            
                            // Trouver les créneaux qui se superposent avec cette tranche horaire
                            $matchingSlots = $daySlots->filter(function($slot) use ($startTime, $endTime) {
                                $slotStart = $slot->start_time->format('H:i');
                                $slotEnd = $slot->end_time->format('H:i');
                                return ($slotStart < $endTime && $slotEnd > $startTime);
                            });
                            
                            // Vérifier s'il y a des conflits pour ce créneau horaire
                            $hasConflictInThisSlot = false;
                            $conflictingDetails = null;
                            
                            if ($conflictsByDayAndTime->isNotEmpty()) {
                                $conflictKey = $day . '_' . $startTime;
                                $conflictingDetails = $conflictsByDayAndTime->get($conflictKey);
                                $hasConflictInThisSlot = $conflictingDetails !== null;
                            }
                            
                            // Compter le nombre de créneaux dans cette cellule
                            $slotCount = $matchingSlots->count();
                            $isOverlapping = $slotCount > 1;
                        @endphp
                        
                        <td class="px-4 py-3 border-l border-gray-800 min-h-16
                            {{ $hasConflictInThisSlot ? 'bg-red-900/10' : '' }}
                            {{ $isOverlapping ? 'bg-yellow-900/10' : '' }}">
                            
                            @if($matchingSlots->isNotEmpty())
                                <!-- Afficher l'alerte de conflit -->
                                @if($hasConflictInThisSlot && $conflictingDetails)
                                <div class="mb-2 p-2 bg-red-900/30 border border-red-700 rounded text-xs">
                                    <div class="flex items-center gap-1 text-red-300 font-medium mb-1">
                                        <span>⚠️</span>
                                        <span>Conflit détecté</span>
                                    </div>
                                    @if($conflictingDetails->conflict_details)
                                    <div class="text-red-200 text-xs mb-1">
                                        {{ $conflictingDetails->conflict_details }}
                                    </div>
                                    @endif
                                    @foreach($conflictingDetails->conflicting_slots as $conflictSlot)
                                    <div class="text-red-200 text-xs truncate">
                                        {{ $conflictSlot->subject_code }} - {{ $conflictSlot->teacher }}
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                                
                                <!-- Afficher les créneaux -->
                                <div class="space-y-2">
                                    @foreach($matchingSlots as $currentSlot)
                                    @php
                                        $isConflictingSlot = false;
                                        if ($hasConflictInThisSlot && $conflictingDetails) {
                                            $isConflictingSlot = $conflictingDetails->conflicting_slots->contains(function($item) use ($currentSlot) {
                                                return $item->slot_id == $currentSlot->id;
                                            });
                                        }
                                    @endphp
                                    <div class="p-2 rounded-lg cursor-pointer hover:opacity-90 transition-opacity group relative
                                        {{ $isConflictingSlot ? 'ring-2 ring-red-500 ring-opacity-50' : '' }}"
                                         style="background-color: {{ $currentSlot->color }}20; border-left: 3px solid {{ $currentSlot->color }}"
                                         title="{{ $currentSlot->subject->name }} - {{ optional($currentSlot->teacherProfile)->full_name ?? 'Professeur non défini' }}">
                                        
                                        <div class="font-medium text-white text-sm flex items-center justify-between mb-1">
                                            <span class="{{ $isConflictingSlot ? 'text-red-300' : '' }}">
                                                {{ $currentSlot->subject->code }}
                                                @if($isConflictingSlot)
                                                <span class="text-red-400 ml-1">⚠️</span>
                                                @endif
                                            </span>
                                            @if($currentSlot->classroom)
                                            <span class="text-xs text-gray-300">🏫{{ $currentSlot->classroom->code }}</span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-xs {{ $isConflictingSlot ? 'text-red-200' : 'text-gray-300' }} truncate mb-1">
                                            {{ $currentSlot->subject->name }}
                                        </div>
                                        
                                        @if($currentSlot->teacherProfile)
                                        <div class="text-xs {{ $isConflictingSlot ? 'text-red-300' : 'text-gray-400' }} flex items-center gap-1 truncate">
                                            👨‍🏫 {{ $currentSlot->teacherProfile->first_name }}
                                        </div>
                                        @elseif($currentSlot->teacher)
                                        <div class="text-xs {{ $isConflictingSlot ? 'text-red-300' : 'text-gray-400' }} flex items-center gap-1 truncate">
                                            👨‍🏫 {{ $currentSlot->teacher->name }}
                                        </div>
                                        @endif
                                        
                                        <!-- Menu d'actions au survol -->
                                        <div class="absolute right-2 top-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <a href="{{ route('timetables.manage-slots', ['timetable' => $timetable->id]) }}?edit={{ $currentSlot->id }}"
                                               class="p-1 text-gray-400 hover:text-white bg-gray-800 rounded">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                            <div class="text-center py-4">
                                <span class="text-gray-600 text-sm">—</span>
                            </div>
                            @endif
                        </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
    <!-- Sections d'informations -->
    <div class="grid grid-cols-1 lg:grid-cols-1 gap-6">
        <!-- Section statistiques par professeur avec barres de progression -->
        <div class="{{ $unassignedAssignments->isNotEmpty() ? 'lg:col-span-1' : 'lg:col-span-3' }}">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Répartition par professeur</h3>
                
                @if($teacherHours->isEmpty())
                <div class="text-center py-4">
                    <span class="text-gray-500 text-sm">Aucun créneau assigné</span>
                </div>
                @else
                @php
                    $maxHours = $teacherHours->max('total_hours');
                @endphp
                
                <div class="space-y-4">
                    @foreach($teacherHours as $teacher)
                    @php
                        $percentage = $maxHours > 0 ? ($teacher->total_hours / $maxHours) * 100 : 0;
                    @endphp
                    <div class="space-y-1">
                        <!-- En-tête -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gray-800 flex items-center justify-center">
                                    <span class="text-gray-400 text-sm">👨‍🏫</span>
                                </div>
                                <span class="text-sm text-white font-medium truncate max-w-[160px]">
                                    {{ $teacher->teacher_profile->first_name ?? $teacher->teacher_name }}
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="font-bold text-white">{{ number_format($teacher->total_hours, 1) }}h</div>
                                <div class="text-xs text-gray-400">{{ $teacher->slot_count }} créneaux</div>
                            </div>
                        </div>
                        
                        <!-- Barre de progression -->
                        <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-500 rounded-full" 
                                 style="width: {{ $percentage }}%"></div>
                        </div>
                        
                        <!-- Spécialisation -->
                        @if($teacher->teacher_profile && $teacher->teacher_profile->specialization)
                        <div class="text-xs text-gray-400">
                            {{ $teacher->teacher_profile->specialization }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                    
                    <!-- Total -->
                    <div class="pt-4 border-t border-gray-800">
                        <div class="flex items-center justify-between font-medium">
                            <span class="text-gray-300">{{ $teacherHours->count() }} professeurs</span>
                            <span class="text-primary-400">
                                {{ number_format($teacherHours->sum('total_hours'), 1) }}h total
                            </span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

    </div>
</div>

<style type="text/css">
    /* Ajoutez dans votre fichier CSS */
    .conflict-cell {
        animation: pulse-conflict 2s infinite;
    }

    @keyframes pulse-conflict {
        0%, 100% {
            background-color: rgba(239, 68, 68, 0.05);
        }
        50% {
            background-color: rgba(239, 68, 68, 0.15);
        }
    }    
</style>
@endsection