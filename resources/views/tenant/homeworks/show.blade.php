{{-- resources/views/tenant/homeworks/show.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $homework->title }}</h1>
                <p class="text-gray-400 text-sm mt-1">Détails du devoir</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('homeworks.index') }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('homeworks.edit', $homework->id) }}"
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
            <!-- Description -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Description
                </h2>
                <p class="text-gray-300 leading-relaxed">
                    {{ $homework->description }}
                </p>
            </div>

            <!-- Instructions -->
            @if($homework->instructions)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Instructions
                </h2>
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($homework->instructions)) !!}
                </div>
            </div>
            @endif

            <!-- Fichiers joints -->
            @php
                $attachments = $homework->attachments;
                if (is_string($attachments)) {
                    $attachments = json_decode($attachments, true);
                }
                if (empty($attachments)) {
                    $attachments = [];
                }
            @endphp
            
            @if(count($attachments) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Documents joints
                </h2>
                <div class="space-y-2">
                    @foreach($attachments as $attachment)
                    <a href="{{ Storage::url($attachment['path']) }}" target="_blank" 
                       class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg hover:bg-gray-750 transition-colors group">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div class="flex-1">
                            <span class="text-gray-300 group-hover:text-white">{{ $attachment['name'] }}</span>
                            <span class="text-xs text-gray-500 ml-2">({{ round($attachment['size'] / 1024, 2) }} KB)</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                        </svg>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Soumissions des élèves -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Soumissions des élèves
                </h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-800">
                        <thead class="bg-gray-850">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Date soumission</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Note</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-800">
                            @forelse($students as $student)
                                @php
                                    $submission = $homework->submissions->where('student_id', $student->id)->first();
                                @endphp
                                <tr class="hover:bg-gray-850">
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($submission)
                                            <span class="px-2 py-1 text-xs rounded-full 
                                                @if($submission->status == 'submitted') bg-yellow-900/50 text-yellow-400
                                                @elseif($submission->status == 'graded') bg-green-900/50 text-green-400
                                                @else bg-gray-900/50 text-gray-400
                                                @endif">
                                                {{ ucfirst($submission->status) }}
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs rounded-full bg-gray-800 text-gray-500">
                                                Non soumis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-400">
                                        @if($submission && $submission->submitted_at)
                                            {{ $submission->submitted_at->format('d/m/Y H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($submission && $submission->score !== null)
                                            <span class="text-sm font-medium {{ $submission->score >= ($homework->max_score / 2) ? 'text-green-400' : 'text-red-400' }}">
                                                {{ $submission->score }}/{{ $homework->max_score }}
                                            </span>
                                        @else
                                            <span class="text-sm text-gray-500">-</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($submission)
                                            <button type="button" 
                                                    onclick="openGradeModal({{ $submission->id }}, {{ $submission->score ?? 'null' }}, '{{ addslashes($submission->feedback) }}')"
                                                    class="text-sm text-primary-400 hover:text-primary-300">
                                                {{ $submission->score !== null ? 'Modifier la note' : 'Noter' }}
                                            </button>
                                            @if($submission->attachments)
                                                <a href="#" onclick="viewSubmission({{ $submission->id }})" class="text-sm text-blue-400 hover:text-blue-300 ml-3">
                                                    Voir
                                                </a>
                                            @endif
                                        @else
                                            <span class="text-sm text-gray-500">En attente</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-8 text-center text-gray-400">
                                        Aucun élève inscrit dans cette classe
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
                            @if($homework->status == 'active') bg-green-900/50 text-green-400
                            @elseif($homework->status == 'expired') bg-red-900/50 text-red-400
                            @else bg-gray-900/50 text-gray-400
                            @endif">
                            @if($homework->status == 'active') Actif
                            @elseif($homework->status == 'expired') Expiré
                            @else Annulé
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Type</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($homework->type == 'homework') bg-blue-900/50 text-blue-400
                            @elseif($homework->type == 'project') bg-purple-900/50 text-purple-400
                            @else bg-green-900/50 text-green-400
                            @endif">
                            @if($homework->type == 'homework') Devoir
                            @elseif($homework->type == 'project') Projet
                            @else Recherche
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
                            <div class="font-medium">{{ $homework->class->name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Matière</div>
                            <div class="font-medium">{{ $homework->subject->name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Professeur</div>
                            <div class="font-medium">{{ $homework->teacher->full_name }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Date limite</div>
                            <div class="font-medium">{{ $homework->due_date->format('d/m/Y') }}
                                @if($homework->due_time) à {{ date('H:i', strtotime($homework->due_time)) }} @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Note maximale</div>
                            <div class="font-medium">{{ $homework->max_score }}/{{ $homework->max_score }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Statistiques</h2>
                <div class="space-y-2">
                    @php
                        $totalStudents = $students->count();
                        $submittedCount = $homework->submissions->count();
                        $gradedCount = $homework->submissions->where('status', 'graded')->count();
                        $averageScore = $homework->submissions->where('score', '!=', null)->avg('score');
                    @endphp
                    <div class="flex justify-between">
                        <span class="text-gray-400">Total élèves</span>
                        <span class="text-white font-medium">{{ $totalStudents }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Soumissions</span>
                        <span class="text-white font-medium">{{ $submittedCount }}/{{ $totalStudents }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Notés</span>
                        <span class="text-white font-medium">{{ $gradedCount }}/{{ $submittedCount }}</span>
                    </div>
                    @if($averageScore)
                    <div class="flex justify-between">
                        <span class="text-gray-400">Moyenne</span>
                        <span class="text-white font-medium">{{ number_format($averageScore, 2) }}/{{ $homework->max_score }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour noter -->
<div id="gradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-900 rounded-xl w-full max-w-md p-6 border border-gray-800">
        <h3 class="text-xl font-bold text-white mb-4">Noter le devoir</h3>
        <form id="gradeForm" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Note (sur {{ $homework->max_score }})</label>
                    <input type="number" name="score" id="score" step="0.01" min="0" max="{{ $homework->max_score }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commentaire</label>
                    <textarea name="feedback" id="feedback" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"></textarea>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" onclick="closeGradeModal()"
                            class="px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white">
                        Enregistrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function openGradeModal(submissionId, currentScore, currentFeedback) {
    const modal = document.getElementById('gradeModal');
    const form = document.getElementById('gradeForm');
    const scoreInput = document.getElementById('score');
    const feedbackInput = document.getElementById('feedback');
    
    form.action = "{{ route('homeworks.grade', $homework->id) }}/" + submissionId;
    scoreInput.value = currentScore !== null ? currentScore : '';
    feedbackInput.value = currentFeedback !== 'null' ? currentFeedback : '';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeGradeModal() {
    const modal = document.getElementById('gradeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function viewSubmission(submissionId) {
    // Implémenter la logique pour voir la soumission
    alert('Fonctionnalité à implémenter: visualisation de la soumission');
}
</script>
@endpush
@endsection