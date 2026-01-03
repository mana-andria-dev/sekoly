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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-red-600/10 border border-red-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-2xl font-bold text-red-400">{{ $conflicts->count() }}</div>
                        <div class="text-sm text-gray-400">Conflits détectés</div>
                    </div>
                    <div class="p-2 bg-red-600/20 rounded-lg">
                        <span class="text-lg text-red-500">⚠️</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-600/10 border border-yellow-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        @php
                            $teacherConflicts = $conflicts->where('type', 'teacher_conflict')->count();
                        @endphp
                        <div class="text-2xl font-bold text-yellow-400">{{ $teacherConflicts }}</div>
                        <div class="text-sm text-gray-400">Conflits de professeurs</div>
                    </div>
                    <div class="p-2 bg-yellow-600/20 rounded-lg">
                        <span class="text-lg text-yellow-500">👨‍🏫</span>
                    </div>
                </div>
            </div>
            
            <div class="bg-orange-600/10 border border-orange-600/20 rounded-xl p-5">
                <div class="flex items-center justify-between">
                    <div>
                        @php
                            $overlapConflicts = $conflicts->where('type', 'overlap')->count();
                        @endphp
                        <div class="text-2xl font-bold text-orange-400">{{ $overlapConflicts }}</div>
                        <div class="text-sm text-gray-400">Chevauchements</div>
                    </div>
                    <div class="p-2 bg-orange-600/20 rounded-lg">
                        <span class="text-lg text-orange-500">⏰</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Conflits par type -->
        @foreach(['teacher_conflict' => 'Conflits de professeurs', 'overlap' => 'Chevauchements'] as $type => $title)
        @php
            $typeConflicts = $conflicts->where('type', $type);
        @endphp
        
        @if($typeConflicts->isNotEmpty())
        <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
            <div class="p-6 border-b border-gray-800">
                <h3 class="text-lg font-semibold text-white">{{ $title }} ({{ $typeConflicts->count() }})</h3>
            </div>
            
            <div class="divide-y divide-gray-800">
                @foreach($typeConflicts as $conflict)
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Icône -->
                        <div class="p-2 {{ $type == 'teacher_conflict' ? 'bg-yellow-600/10' : 'bg-orange-600/10' }} rounded-lg">
                            @if($type == 'teacher_conflict')
                            <span class="text-lg text-yellow-500">👨‍🏫</span>
                            @else
                            <span class="text-lg text-orange-500">⏰</span>
                            @endif
                        </div>
                        
                        <!-- Détails du conflit -->
                        <div class="flex-1">
                            <h4 class="font-medium text-white mb-2">
                                {{ $conflict['slot1']->subject->name }} 
                                @if($conflict['type'] == 'teacher_conflict')
                                - Conflit de professeur
                                @else
                                - Chevauchement horaire
                                @endif
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                <!-- Premier créneau -->
                                <div class="p-3 bg-gray-800/50 rounded-lg"
                                     style="border-left: 3px solid {{ $conflict['slot1']->color }}">
                                    <div class="text-sm font-medium text-white">{{ $conflict['slot1']->subject->name }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $conflict['slot1']->start_time->format('H:i') }} - {{ $conflict['slot1']->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($conflict['slot1']->teacherProfile)
                                        👨‍🏫 {{ $conflict['slot1']->teacherProfile->full_name }}
                                        @elseif($conflict['slot1']->teacher)
                                        👨‍🏫 {{ $conflict['slot1']->teacher->name }}
                                        @endif
                                        @if($conflict['slot1']->classroom)
                                        • 🏫 {{ $conflict['slot1']->classroom->name }}
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Deuxième créneau -->
                                <div class="p-3 bg-gray-800/50 rounded-lg"
                                     style="border-left: 3px solid {{ $conflict['slot2']->color }}">
                                    <div class="text-sm font-medium text-white">{{ $conflict['slot2']->subject->name }}</div>
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $conflict['slot2']->start_time->format('H:i') }} - {{ $conflict['slot2']->end_time->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        @if($conflict['slot2']->teacherProfile)
                                        👨‍🏫 {{ $conflict['slot2']->teacherProfile->full_name }}
                                        @elseif($conflict['slot2']->teacher)
                                        👨‍🏫 {{ $conflict['slot2']->teacher->name }}
                                        @endif
                                        @if($conflict['slot2']->classroom)
                                        • 🏫 {{ $conflict['slot2']->classroom->name }}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-800">
                                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $conflict['slot1']->id }}"
                                   class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white">
                                    Modifier le 1er créneau
                                </a>
                                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}?edit={{ $conflict['slot2']->id }}"
                                   class="px-3 py-1.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white">
                                    Modifier le 2ème créneau
                                </a>
                                <form action="{{ route('timetable-slots.destroy', ['tenant' => app('tenant')->name, 'slot' => $conflict['slot1']->id]) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Supprimer le premier créneau ?')"
                                            class="px-3 py-1.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endforeach
        
        <!-- Actions globales -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4">Résoudre les conflits</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('timetables.manage-slots', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="p-4 bg-gray-800 hover:bg-gray-700 rounded-lg text-center transition-colors">
                    <div class="text-white font-medium mb-1">Gérer les créneaux</div>
                    <div class="text-sm text-gray-400">Modifier manuellement les créneaux en conflit</div>
                </a>
                
                <form action="{{ route('timetables.generate-from-assignments', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" 
                      method="POST">
                    @csrf
                    <button type="submit" 
                            onclick="return confirm('Regénérer automatiquement ? Cela écrasera tous les créneaux existants.')"
                            class="w-full h-full p-4 bg-primary-600 hover:bg-primary-700 rounded-lg text-center transition-colors">
                        <div class="text-white font-medium mb-1">Regénérer automatiquement</div>
                        <div class="text-sm text-white/80">Recréer l'emploi du temps à partir des affectations</div>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection