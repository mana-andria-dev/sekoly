{{-- resources/views/tenant/exams/show.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $exam->title }}</h1>
                <p class="text-gray-400 text-sm mt-1">Détails de l'examen</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('exams.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('exams.edit', $exam->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('exams.results', $exam->id) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Résultats
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            @if($exam->description)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Description
                </h2>
                <p class="text-gray-300 leading-relaxed">
                    {{ $exam->description }}
                </p>
            </div>
            @endif

            <!-- Sujets abordés -->
            @php
                $topics = $exam->topics;
                if (is_string($topics)) {
                    $topics = json_decode($topics, true);
                }
                if (empty($topics)) {
                    $topics = [];
                }
            @endphp
            
            @if(count($topics) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Sujets abordés
                </h2>
                <ul class="space-y-2">
                    @foreach($topics as $topic)
                    <li class="flex items-start gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ $topic }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Instructions -->
            @php
                $instructions = $exam->instructions;
                if (is_string($instructions)) {
                    $instructions = json_decode($instructions, true);
                }
                if (empty($instructions)) {
                    $instructions = [];
                }
            @endphp
            
            @if(count($instructions) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Instructions
                </h2>
                <ul class="space-y-2">
                    @foreach($instructions as $instruction)
                    <li class="flex items-start gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-blue-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ $instruction }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                        Résultats des élèves
                    </h2>
                    <a href="{{ route('exams.results', $exam->id) }}"
                       class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Saisir les notes
                    </a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-850">
                             <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Note</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Appréciation</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                             </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($students as $student)
                                @php
                                    $result = $exam->results->where('student_id', $student->id)->first();
                                    $percentage = $result ? ($result->score / $exam->max_score) * 100 : 0;
                                @endphp
                                <tr class="hover:bg-gray-850">
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                                        <div class="text-xs text-gray-500">#{{ $student->id }}</div>
                                     </td>
                                    <td class="px-4 py-3">
                                        @if($result)
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-semibold {{ $result->score >= ($exam->max_score / 2) ? 'text-green-400' : 'text-red-400' }}">
                                                    {{ number_format($result->score, 2) }}
                                                </span>
                                                <span class="text-sm text-gray-500">/ {{ $exam->max_score }}</span>
                                                <span class="text-xs text-gray-500 ml-1">({{ number_format($percentage, 1) }}%)</span>
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">Non noté</span>
                                        @endif
                                     </td>
                                    <td class="px-4 py-3">
                                        @if($result && $result->feedback)
                                            <div class="text-sm text-gray-300 max-w-xs">
                                                {{ $result->feedback }}
                                            </div>
                                        @else
                                            <span class="text-sm text-gray-500 italic">-</span>
                                        @endif
                                     </td>
                                    <td class="px-4 py-3">
                                        @if($result)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-900/50 text-green-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Noté
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-900/50 text-yellow-400">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                En attente
                                            </span>
                                        @endif
                                     </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <p class="text-gray-400">Aucun élève inscrit dans cette classe</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Statistiques des résultats -->
                @if($resultsCount > 0)
                <div class="mt-6 pt-4 border-t border-gray-800">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-white">{{ $resultsCount }}/{{ $totalStudents }}</div>
                            <div class="text-xs text-gray-400 mt-1">Élèves notés</div>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-primary-400">{{ number_format($averageScore, 2) }}</div>
                            <div class="text-xs text-gray-400 mt-1">Moyenne / {{ $exam->max_score }}</div>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                            @php
                                $passingScore = $exam->max_score / 2;
                                $passedCount = $exam->results->where('score', '>=', $passingScore)->count();
                                $passRate = $exam->results->count() > 0 ? ($passedCount / $exam->results->count()) * 100 : 0;
                            @endphp
                            <div class="text-2xl font-bold text-green-400">{{ number_format($passRate, 1) }}%</div>
                            <div class="text-xs text-gray-400 mt-1">Taux de réussite</div>
                        </div>
                        <div class="bg-gray-800/50 rounded-lg p-3 text-center">
                            <div class="text-2xl font-bold text-purple-400">{{ $exam->coefficient }}</div>
                            <div class="text-xs text-gray-400 mt-1">Coefficient</div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Colonne droite - Informations complémentaires -->
        <div class="space-y-6">
            <!-- Statut et type -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Statut</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Statut</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($exam->status == 'scheduled') bg-yellow-900/50 text-yellow-400
                            @elseif($exam->status == 'ongoing') bg-blue-900/50 text-blue-400
                            @elseif($exam->status == 'completed') bg-green-900/50 text-green-400
                            @else bg-red-900/50 text-red-400
                            @endif">
                            @if($exam->status == 'scheduled') Planifié
                            @elseif($exam->status == 'ongoing') En cours
                            @elseif($exam->status == 'completed') Terminé
                            @else Annulé
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Type</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($exam->type == 'quiz') bg-blue-900/50 text-blue-400
                            @elseif($exam->type == 'test') bg-green-900/50 text-green-400
                            @elseif($exam->type == 'trimester') bg-purple-900/50 text-purple-400
                            @elseif($exam->type == 'semester') bg-orange-900/50 text-orange-400
                            @else bg-red-900/50 text-red-400
                            @endif">
                            @if($exam->type == 'quiz') Quiz
                            @elseif($exam->type == 'test') Test
                            @elseif($exam->type == 'trimester') Trimestriel
                            @elseif($exam->type == 'semester') Semestriel
                            @else Final
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Détails -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Détails</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Classe</div>
                            <div class="font-medium">{{ $exam->class->name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Matière</div>
                            <div class="font-medium">{{ $exam->subject->name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Professeur</div>
                            <div class="font-medium">{{ $exam->teacher->full_name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Date</div>
                            <div class="font-medium">{{ $exam->exam_date->format('d/m/Y') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Horaire</div>
                            <div class="font-medium">{{ date('H:i', strtotime($exam->start_time)) }} - {{ date('H:i', strtotime($exam->end_time)) }}</div>
                            <div class="text-xs text-gray-500">Durée: {{ $exam->duration_minutes }} minutes</div>
                        </div>
                    </div>

                    @if($exam->location)
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Lieu</div>
                            <div class="font-medium">{{ $exam->location }}</div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Note maximale</div>
                            <div class="font-medium">{{ $exam->max_score }}/{{ $exam->max_score }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Coefficient</div>
                            <div class="font-medium">{{ $exam->coefficient }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Statistiques</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total élèves</span>
                        <span class="text-white font-medium">{{ $totalStudents ?? $students->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Résultats enregistrés</span>
                        <span class="text-white font-medium">{{ $resultsCount ?? $exam->results->count() }}/{{ $totalStudents ?? $students->count() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Notés</span>
                        <span class="text-white font-medium">{{ $gradedCount ?? $exam->results->where('score', '!=', null)->count() }}</span>
                    </div>
                    @if(($averageScore ?? $exam->results->avg('score')) !== null)
                    <div class="flex justify-between pt-2 border-t border-gray-800">
                        <span class="text-gray-400">Moyenne de la classe</span>
                        <span class="text-primary-400 font-semibold">{{ number_format($averageScore ?? $exam->results->avg('score'), 2) }}/{{ $exam->max_score }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Taux de réussite</span>
                        @php
                            $passingScore = $exam->max_score / 2;
                            $passedCount = $exam->results->where('score', '>=', $passingScore)->count();
                            $passRate = $exam->results->count() > 0 ? ($passedCount / $exam->results->count()) * 100 : 0;
                        @endphp
                        <span class="text-green-400 font-semibold">{{ number_format($passRate, 1) }}%</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection