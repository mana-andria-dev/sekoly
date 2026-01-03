@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary-600/10 rounded-lg">
                        <span class="text-xl text-primary-600">👨‍🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Emploi du temps - {{ $teacher->full_name ?? $teacher->user->name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">Horaire hebdomadaire du professeur</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <button onclick="window.print()"
                        class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Imprimer
                </button>
                <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
                   class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Fiche du professeur
                </a>
            </div>
        </div>
    </div>

    <!-- Informations du professeur -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">Heures/semaine</div>
                    <div class="text-2xl font-bold text-white">{{ $weeklyHours }}h</div>
                </div>
                <div class="p-2 bg-blue-600/10 rounded-lg">
                    <span class="text-lg text-blue-500">⏰</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">Cours planifiés</div>
                    <div class="text-2xl font-bold text-white">{{ $slots->flatten()->count() }}</div>
                </div>
                <div class="p-2 bg-green-600/10 rounded-lg">
                    <span class="text-lg text-green-500">📚</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">Classes</div>
                    <div class="text-2xl font-bold text-white">
                        {{ $slots->flatten()->pluck('timetable.class_id')->unique()->count() }}
                    </div>
                </div>
                <div class="p-2 bg-purple-600/10 rounded-lg">
                    <span class="text-lg text-purple-500">🏫</span>
                </div>
            </div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-gray-400">Matières</div>
                    <div class="text-2xl font-bold text-white">
                        {{ $slots->flatten()->pluck('subject_id')->unique()->count() }}
                    </div>
                </div>
                <div class="p-2 bg-orange-600/10 rounded-lg">
                    <span class="text-lg text-orange-500">📖</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Emploi du temps détaillé -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Vue par jour -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Planning hebdomadaire</h3>
                </div>
                
                <div class="divide-y divide-gray-800">
                    @foreach($slots as $dayName => $daySlots)
                    <div class="p-6">
                        <h4 class="text-md font-semibold text-white mb-4">{{ $dayName }}</h4>
                        
                        <div class="space-y-4">
                            @forelse($daySlots as $slot)
                            <div class="p-4 bg-gray-800/50 rounded-lg hover:bg-gray-800 transition-colors"
                                 style="border-left: 4px solid {{ $slot->color }}">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <span class="font-semibold text-white">{{ $slot->subject->name }}</span>
                                            <span class="text-xs px-2 py-1 bg-gray-700 rounded">{{ $slot->subject->code }}</span>
                                            <span class="text-sm text-gray-400">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-3">
                                            <div class="space-y-1">
                                                <div class="text-sm text-gray-400">Classe</div>
                                                <div class="text-white font-medium">
                                                    {{ $slot->timetable->class->name ?? 'N/A' }}
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-1">
                                                <div class="text-sm text-gray-400">Salle</div>
                                                <div class="text-white font-medium">
                                                    {{ $slot->classroom->name ?? 'Non attribuée' }}
                                                </div>
                                            </div>
                                            
                                            <div class="space-y-1">
                                                <div class="text-sm text-gray-400">Durée</div>
                                                <div class="text-white font-medium">{{ $slot->duration }}h</div>
                                            </div>
                                            
                                            <div class="space-y-1">
                                                <div class="text-sm text-gray-400">Type</div>
                                                <div class="text-white font-medium">{{ ucfirst($slot->timetable->type ?? 'weekly') }}</div>
                                            </div>
                                        </div>
                                        
                                        @if($slot->notes)
                                        <div class="mt-3 pt-3 border-t border-gray-700">
                                            <div class="text-sm text-gray-400 mb-1">Notes</div>
                                            <div class="text-sm text-gray-300">{{ $slot->notes }}</div>
                                        </div>
                                        @endif
                                    </div>
                                    
                                    <div class="ml-4">
                                        <a href="{{ route('classes.timetable', ['tenant' => app('tenant')->name, 'class' => $slot->timetable->class_id]) }}"
                                           class="p-2 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                           title="Voir emploi du temps de la classe">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-6">
                                <div class="text-gray-500">
                                    <svg class="w-12 h-12 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-medium text-gray-300 mt-4">Aucun cours ce jour</h3>
                            </div>
                            @endforelse
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Vue synthétique et statistiques -->
        <div>
            <!-- Vue calendrier simplifiée -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-white mb-4">Vue synthétique</h3>
                
                <div class="space-y-3">
                    @foreach($slots as $dayName => $daySlots)
                    @if($daySlots->isNotEmpty())
                    <div class="p-3 bg-gray-800/50 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-white">{{ $dayName }}</span>
                            <span class="text-sm text-gray-400">
                                {{ $daySlots->sum('duration') }}h
                            </span>
                        </div>
                        
                        <div class="space-y-2">
                            @foreach($daySlots->take(3) as $slot)
                            <div class="text-sm">
                                <div class="flex items-center justify-between">
                                    <span class="text-gray-300 truncate">{{ $slot->subject->code }}</span>
                                    <span class="text-gray-400 text-xs">{{ $slot->start_time->format('H:i') }}</span>
                                </div>
                                <div class="text-xs text-gray-500 truncate">{{ $slot->timetable->class->name ?? '' }}</div>
                            </div>
                            @endforeach
                            
                            @if($daySlots->count() > 3)
                            <div class="text-xs text-gray-500 text-center">
                                +{{ $daySlots->count() - 3 }} autres cours
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
            
            <!-- Statistiques par matière -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Heures par matière</h3>
                
                <div class="space-y-3">
                    @php
                        $subjectHours = [];
                        foreach($slots->flatten() as $slot) {
                            $subjectName = $slot->subject->name;
                            if (!isset($subjectHours[$subjectName])) {
                                $subjectHours[$subjectName] = 0;
                            }
                            $subjectHours[$subjectName] += $slot->duration;
                        }
                        arsort($subjectHours);
                    @endphp
                    
                    @foreach($subjectHours as $subject => $hours)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-primary-600"></div>
                            <span class="text-gray-300 text-sm truncate">{{ $subject }}</span>
                        </div>
                        <span class="text-gray-400 text-sm">{{ number_format($hours, 1) }}h</span>
                    </div>
                    @endforeach
                    
                    <!-- Total -->
                    <div class="pt-3 border-t border-gray-800 mt-3">
                        <div class="flex items-center justify-between font-medium">
                            <span class="text-white">Total</span>
                            <span class="text-primary-400">{{ $weeklyHours }}h</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Téléchargement -->
            <div class="mt-6 p-4 bg-gray-800/50 rounded-lg">
                <p class="text-sm text-gray-400 mb-3">Exporter l'emploi du temps</p>
                <div class="flex flex-col gap-2">
                    <button onclick="window.print()"
                            class="w-full px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        📄 Imprimer
                    </button>
                    <button onclick="exportToPDF()"
                            class="w-full px-4 py-2 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium transition-all duration-200">
                        📥 Exporter PDF
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportToPDF() {
    alert('Fonction d\'export PDF à implémenter');
    // À implémenter: génération et téléchargement PDF
}
</script>

// Style d'impression
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .bg-gray-900 {
        background: white !important;
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
}
</style>
@endsection