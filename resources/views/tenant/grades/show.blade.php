{{-- resources/views/tenant/grades/show.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Détail de la note</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $grade->student->first_name }} {{ $grade->student->last_name }} - {{ $grade->subject->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('grades.index', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('grades.edit', [app('tenant')->name, $grade->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Détails de la note -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Informations de la note
                </h2>
                
                <div class="space-y-4">
                    @if($grade->title)
                    <div>
                        <label class="text-sm text-gray-400">Titre</label>
                        <p class="text-white">{{ $grade->title }}</p>
                    </div>
                    @endif
                    
                    <div>
                        <label class="text-sm text-gray-400">Note</label>
                        <div class="flex items-center gap-3">
                            <span class="text-3xl font-bold {{ $grade->score >= ($grade->max_score / 2) ? 'text-green-400' : 'text-red-400' }}">
                                {{ number_format($grade->score, 2) }}
                            </span>
                            <span class="text-xl text-gray-500">/ {{ $grade->max_score }}</span>
                            <span class="text-sm text-gray-500">({{ number_format($grade->percentage, 1) }}%)</span>
                            <span class="text-sm text-gray-500">| Sur 20: {{ number_format($grade->score_over_20, 2) }}/20</span>
                        </div>
                    </div>
                    
                    @if($grade->comment)
                    <div>
                        <label class="text-sm text-gray-400">Commentaire</label>
                        <p class="text-gray-300">{{ $grade->comment }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Informations complémentaires -->
        <div class="space-y-6">
            <!-- Métadonnées -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Informations</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Type</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($grade->grade_type == 'homework') bg-blue-900/50 text-blue-400
                            @elseif($grade->grade_type == 'test') bg-purple-900/50 text-purple-400
                            @elseif($grade->grade_type == 'quiz') bg-green-900/50 text-green-400
                            @else bg-yellow-900/50 text-yellow-400
                            @endif">
                            {{ ucfirst($grade->grade_type) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Coefficient</span>
                        <span class="text-white font-medium">{{ $grade->coefficient }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Date</span>
                        <span class="text-white">{{ $grade->grade_date->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Période</span>
                        <span class="text-white">
                            @if($grade->period == 'trimester1') 1er Trimestre
                            @elseif($grade->period == 'trimester2') 2ème Trimestre
                            @elseif($grade->period == 'trimester3') 3ème Trimestre
                            @elseif($grade->period == 'annual') Annuel
                            @else Non spécifiée
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Contexte -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Contexte</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Classe</div>
                            <div class="font-medium">{{ $grade->class->name }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Matière</div>
                            <div class="font-medium">{{ $grade->subject->name }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Professeur</div>
                            <div class="font-medium">{{ $grade->teacher->full_name ?? 'Non défini' }}</div>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Élève</div>
                            <div class="font-medium">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Audit -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Audit</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Créé le</span>
                        <span class="text-gray-300">{{ $grade->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dernière modification</span>
                        <span class="text-gray-300">{{ $grade->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection