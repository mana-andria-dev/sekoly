{{-- resources/views/tenant/exams/edit.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Modifier l'examen</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $exam->title }}</p>
            </div>
            <a href="{{ route('exams.index', app('tenant')->name) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <form action="{{ route('exams.update', [app('tenant')->name, $exam->id]) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Informations générales -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Informations générales
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Titre *</label>
                        <input type="text" name="title" required value="{{ old('title', $exam->title) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('title')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                        <select name="type" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="quiz" {{ old('type', $exam->type) == 'quiz' ? 'selected' : '' }}>Quiz</option>
                            <option value="test" {{ old('type', $exam->type) == 'test' ? 'selected' : '' }}>Test</option>
                            <option value="trimester" {{ old('type', $exam->type) == 'trimester' ? 'selected' : '' }}>Trimestriel</option>
                            <option value="semester" {{ old('type', $exam->type) == 'semester' ? 'selected' : '' }}>Semestriel</option>
                            <option value="final" {{ old('type', $exam->type) == 'final' ? 'selected' : '' }}>Final</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Statut *</label>
                        <select name="status" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="scheduled" {{ old('status', $exam->status) == 'scheduled' ? 'selected' : '' }}>Planifié</option>
                            <option value="ongoing" {{ old('status', $exam->status) == 'ongoing' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ old('status', $exam->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                            <option value="cancelled" {{ old('status', $exam->status) == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                        </select>
                        @error('status')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Classe *</label>
                        <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $exam->class_id) == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Matière *</label>
                        <select name="subject_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $exam->subject_id) == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Professeur *</label>
                        <select name="teacher_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner un professeur</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $exam->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Lieu</label>
                        <input type="text" name="location" value="{{ old('location', $exam->location) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('location')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                        <input type="date" name="exam_date" required value="{{ old('exam_date', $exam->exam_date->format('Y-m-d')) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('exam_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Heure début *</label>
                        <input type="time" name="start_time" required value="{{ old('start_time', date('H:i', strtotime($exam->start_time))) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('start_time')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Heure fin *</label>
                        <input type="time" name="end_time" required value="{{ old('end_time', date('H:i', strtotime($exam->end_time))) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('end_time')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Durée (minutes) *</label>
                        <input type="number" name="duration_minutes" required value="{{ old('duration_minutes', $exam->duration_minutes) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"
                               min="15" max="360">
                        @error('duration_minutes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Note maximale *</label>
                        <input type="number" name="max_score" required value="{{ old('max_score', $exam->max_score) }}" step="0.5"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"
                               min="1" max="100">
                        @error('max_score')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Coefficient *</label>
                        <input type="number" name="coefficient" required value="{{ old('coefficient', $exam->coefficient) }}" step="0.5"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"
                               min="0.5" max="5">
                        @error('coefficient')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Description
                </h2>

                <div>
                    <textarea name="description" rows="4"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description', $exam->description) }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Sujets abordés -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Sujets abordés
                </h2>

                <div id="topics-container">
                    @php
                        $topics = old('topics', $exam->topics ?? []);
                        if (is_string($topics)) {
                            $topics = json_decode($topics, true);
                        }
                        if (empty($topics)) {
                            $topics = [''];
                        }
                    @endphp
                    @foreach($topics as $index => $topic)
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="topics[]" value="{{ $topic }}"
                               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white"
                               placeholder="Sujet {{ $index + 1 }}">
                        <button type="button" class="remove-topic text-red-400 hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-topic" class="mt-2 text-sm text-primary-400 hover:text-primary-300">
                    + Ajouter un sujet
                </button>
            </div>

            <!-- Instructions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Instructions
                </h2>

                <div id="instructions-container">
                    @php
                        $instructions = old('instructions', $exam->instructions ?? []);
                        if (is_string($instructions)) {
                            $instructions = json_decode($instructions, true);
                        }
                        if (empty($instructions)) {
                            $instructions = [''];
                        }
                    @endphp
                    @foreach($instructions as $index => $instruction)
                    <div class="flex gap-2 mb-2">
                        <input type="text" name="instructions[]" value="{{ $instruction }}"
                               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white"
                               placeholder="Instruction {{ $index + 1 }}">
                        <button type="button" class="remove-instruction text-red-400 hover:text-red-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-instruction" class="mt-2 text-sm text-primary-400 hover:text-primary-300">
                    + Ajouter une instruction
                </button>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('exams.show', [app('tenant')->name, $exam->id]) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Mettre à jour
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des sujets
    const topicsContainer = document.getElementById('topics-container');
    const addTopicBtn = document.getElementById('add-topic');
    
    if (addTopicBtn) {
        addTopicBtn.addEventListener('click', function() {
            const index = topicsContainer.children.length;
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
                <input type="text" name="topics[]" class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="Sujet ${index + 1}">
                <button type="button" class="remove-topic text-red-400 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            topicsContainer.appendChild(div);
        });
    }
    
    // Gestion des instructions
    const instructionsContainer = document.getElementById('instructions-container');
    const addInstructionBtn = document.getElementById('add-instruction');
    
    if (addInstructionBtn) {
        addInstructionBtn.addEventListener('click', function() {
            const index = instructionsContainer.children.length;
            const div = document.createElement('div');
            div.className = 'flex gap-2 mb-2';
            div.innerHTML = `
                <input type="text" name="instructions[]" class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="Instruction ${index + 1}">
                <button type="button" class="remove-instruction text-red-400 hover:text-red-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            `;
            instructionsContainer.appendChild(div);
        });
    }
    
    // Suppression dynamique
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-topic')) {
            e.target.closest('.flex').remove();
        }
        if (e.target.closest('.remove-instruction')) {
            e.target.closest('.flex').remove();
        }
    });
});
</script>
@endpush
@endsection