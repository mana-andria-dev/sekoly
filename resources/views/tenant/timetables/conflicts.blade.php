@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-red-600/10 rounded-lg">
                        <span class="text-xl text-red-600">⚠️</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Conflits d'horaires</h1>
                        <p class="text-gray-400 text-sm mt-1">{{ $timetable->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('timetables.show', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Retour
                </a>
            </div>
        </div>
    </div>

    @if($conflicts->isEmpty())
    <!-- Aucun conflit -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-8 text-center">
        <div class="text-green-500">
            <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-medium text-white mt-4">Aucun conflit détecté</h3>
        <p class="text-sm text-gray-400 mt-2">
            Tous les créneaux sont correctement planifiés sans chevauchement.
        </p>
    </div>
    @else
    <!-- Liste des conflits -->
    <div class="space-y-6">
        <!-- Statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-red-400">{{ $conflicts->count() }}</div>
                        <div class="text-sm text-gray-400">Total conflits</div>
                    </div>
                    <div class="p-2 bg-red-600/20 rounded-lg">
                        <span class="text-lg text-red-500">⚠️</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-600/10 border border-yellow-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-yellow-400">{{ $teacherConflictsCount }}</div>
                        <div class="text-sm text-gray-400">Professeurs</div>
                    </div>
                    <div class="p-2 bg-yellow-600/20 rounded-lg">
                        <span class="text-lg text-yellow-500">👨‍🏫</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-600/10 border border-orange-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-orange-400">{{ $classroomConflictsCount }}</div>
                        <div class="text-sm text-gray-400">Salles</div>
                    </div>
                    <div class="p-2 bg-orange-600/20 rounded-lg">
                        <span class="text-lg text-orange-500">🏫</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-blue-600/10 border border-blue-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-blue-400">{{ $overlapConflictsCount }}</div>
                        <div class="text-sm text-gray-400">Chevauchements</div>
                    </div>
                    <div class="p-2 bg-blue-600/20 rounded-lg">
                        <span class="text-lg text-blue-500">⏰</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conflits par type -->
        @foreach([
            'teacher_conflict' => ['Conflits de professeurs', 'yellow', '👨‍🏫'],
            'teacher_profile_conflict' => ['Conflits de professeurs (profil)', 'yellow', '👨‍🏫'],
            'classroom_conflict' => ['Conflits de salles', 'orange', '🏫'],
            'time_overlap' => ['Chevauchements horaires', 'blue', '⏰']
        ] as $type => [$title, $color, $icon])
        
        @php
            $typeConflicts = $conflicts->where('type', $type);
        @endphp
        
        @if($typeConflicts->isNotEmpty())
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="p-6 border-b border-gray-800">
                <div class="flex items-center gap-2">
                    <span class="text-{{ $color }}-500">{{ $icon }}</span>
                    <h3 class="text-lg font-semibold text-white">{{ $title }} ({{ $typeConflicts->count() }})</h3>
                </div>
            </div>
            
            <div class="divide-y divide-gray-800">
                @foreach($typeConflicts as $conflict)
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Icône -->
                        <div class="p-2 bg-{{ $color }}-600/10 rounded-lg">
                            <span class="text-lg text-{{ $color }}-500">{{ $icon }}</span>
                        </div>
                        
                        <!-- Détails du conflit -->
                        <div class="flex-1">
                            <!-- Description du conflit -->
                            <div class="mb-4 p-3 bg-gray-800/50 rounded-lg border-l-4 border-{{ $color }}-500">
                                <p class="text-white text-sm font-medium">
                                    {{ $conflict->conflict_details ?? 'Conflit détecté' }}
                                </p>
                                <p class="text-gray-400 text-xs mt-1">
                                    Jour: 
                                    @switch($conflict->day_of_week)
                                        @case(1) Lundi @break
                                        @case(2) Mardi @break
                                        @case(3) Mercredi @break
                                        @case(4) Jeudi @break
                                        @case(5) Vendredi @break
                                        @case(6) Samedi @break
                                        @case(7) Dimanche @break
                                        @default Jour {{ $conflict->day_of_week }}
                                    @endswitch
                                    • {{ $conflict->start_time->format('H:i') }} - {{ $conflict->end_time->format('H:i') }}
                                </p>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                <!-- Premier créneau -->
                                @if($conflict->slot1)
                                <div class="p-3 bg-gray-800/50 rounded-lg"
                                     style="border-left: 3px solid {{ $conflict->slot1->color ?? '#3B82F6' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-sm font-medium text-white">{{ $conflict->slot1->subject->name ?? 'Matière inconnue' }}</div>
                                        <div class="text-xs px-2 py-1 bg-gray-700 rounded">
                                            {{ $conflict->slot1->subject->code ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $conflict->slot1->start_time->format('H:i') }} - {{ $conflict->slot1->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 space-y-1">
                                        @if($conflict->slot1->teacherProfile)
                                        <div class="flex items-center gap-1">
                                            <span>👨‍🏫</span>
                                            <span>{{ $conflict->slot1->teacherProfile->first_name }} {{ $conflict->slot1->teacherProfile->last_name }}</span>
                                        </div>
                                        @elseif($conflict->slot1->teacher)
                                        <div class="flex items-center gap-1">
                                            <span>👨‍🏫</span>
                                            <span>{{ $conflict->slot1->teacher->name }}</span>
                                        </div>
                                        @endif
                                        @if($conflict->slot1->classroom)
                                        <div class="flex items-center gap-1">
                                            <span>🏫</span>
                                            <span>{{ $conflict->slot1->classroom->code ?? $conflict->slot1->classroom->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                                
                                <!-- Deuxième créneau -->
                                @if($conflict->slot2)
                                <div class="p-3 bg-gray-800/50 rounded-lg"
                                     style="border-left: 3px solid {{ $conflict->slot2->color ?? '#EF4444' }}">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-sm font-medium text-white">{{ $conflict->slot2->subject->name ?? 'Matière inconnue' }}</div>
                                        <div class="text-xs px-2 py-1 bg-gray-700 rounded">
                                            {{ $conflict->slot2->subject->code ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $conflict->slot2->start_time->format('H:i') }} - {{ $conflict->slot2->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1 space-y-1">
                                        @if($conflict->slot2->teacherProfile)
                                        <div class="flex items-center gap-1">
                                            <span>👨‍🏫</span>
                                            <span>{{ $conflict->slot2->teacherProfile->first_name }} {{ $conflict->slot2->teacherProfile->last_name }}</span>
                                        </div>
                                        @elseif($conflict->slot2->teacher)
                                        <div class="flex items-center gap-1">
                                            <span>👨‍🏫</span>
                                            <span>{{ $conflict->slot2->teacher->name }}</span>
                                        </div>
                                        @endif
                                        @if($conflict->slot2->classroom)
                                        <div class="flex items-center gap-1">
                                            <span>🏫</span>
                                            <span>{{ $conflict->slot2->classroom->code ?? $conflict->slot2->classroom->name }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex flex-wrap items-center gap-3 mt-4 pt-4 border-t border-gray-800">
                                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $conflict->slot1->id }}"
                                   class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white">
                                    Modifier le créneau
                                </a>

                                <!---
                                @if($conflict->slot1)
                                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $conflict->slot1->id }}"
                                   class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white">
                                    Modifier le 1er créneau
                                </a>
                                @endif
                                
                                @if($conflict->slot2)
                                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $conflict->slot2->id }}"
                                   class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white">
                                    Modifier le 2ème créneau
                                </a>
                                @endif
                                
                                @if($conflict->slot1)
                                <form action="{{ route('timetable-slots.destroy', ['tenant' => app('tenant')->name, 'timetable_slot' => $conflict->slot1->id]) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Supprimer ce créneau ?')"
                                            class="px-3 py-1.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium">
                                        Supprimer le 1er
                                    </button>
                                </form>
                                @endif
                                -->
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
        
        <!-- Résumé -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <div class="mb-4">
                <h3 class="text-lg font-semibold text-white mb-2">Résumé des conflits</h3>
                <p class="text-sm text-gray-400">
                    Cet emploi du temps contient {{ $conflicts->count() }} conflit(s) à résoudre.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
                <div class="p-4 bg-gray-800 hover:bg-gray-700 rounded-lg text-center transition-colors cursor-pointer"
                     onclick="window.location.href='{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}'">
                    <div class="text-white font-medium mb-1">📋 Gérer les créneaux</div>
                    <div class="text-sm text-gray-400">Modifier manuellement les créneaux en conflit</div>
                </div>
            </div>
            
            <!-- Conseils pour résoudre les conflits -->
            <div class="mt-6 pt-6 border-t border-gray-800">
                <h4 class="text-sm font-medium text-white mb-3">Conseils pour résoudre les conflits :</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Pour les conflits de professeurs : vérifiez que le même professeur n'est pas programmé sur deux cours en même temps</span>
                    </li>
                    <!-- 
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Pour les conflits de salles : attribuez des salles différentes ou ajustez les horaires</span> 
                    </li>
                    -->
                    <li class="flex items-start gap-2">
                        <span class="text-green-500 mt-0.5">✓</span>
                        <span>Pour les chevauchements : déplacez l'un des créneaux à un autre horaire</span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @endif
</div>

<style type="text/css">
    /* Ajoutez ces styles pour améliorer l'affichage */
    .bg-yellow-600\/10 { background-color: rgba(217, 119, 6, 0.1); }
    .border-yellow-600\/20 { border-color: rgba(217, 119, 6, 0.2); }
    .text-yellow-500 { color: #f59e0b; }

    .bg-orange-600\/10 { background-color: rgba(194, 65, 12, 0.1); }
    .border-orange-600\/20 { border-color: rgba(194, 65, 12, 0.2); }
    .text-orange-500 { color: #f97316; }

    .bg-blue-600\/10 { background-color: rgba(37, 99, 235, 0.1); }
    .border-blue-600\/20 { border-color: rgba(37, 99, 235, 0.2); }
    .text-blue-500 { color: #3b82f6; }

    .bg-red-600\/10 { background-color: rgba(220, 38, 38, 0.1); }
    .border-red-600\/20 { border-color: rgba(220, 38, 38, 0.2); }
    .text-red-500 { color: #ef4444; }    
</style>
@endsection