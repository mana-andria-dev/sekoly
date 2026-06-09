@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">
                    {{ isset($lesson) ? 'Modifier la leçon' : 'Nouvelle leçon' }}
                </h1>
                <p class="text-gray-400 text-sm mt-1">
                    {{ isset($lesson) ? 'Modifiez les informations de la leçon' : 'Créez une nouvelle leçon' }}
                </p>
            </div>
            <a href="{{ route('lessons.index') }}"
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

    <form action="{{ isset($lesson) ? route('lessons.update', $lesson->id) : route('lessons.store') }}" 
          method="POST">
        @csrf
        @if(isset($lesson))
            @method('PUT')
        @endif

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
                        <input type="text" name="title" required value="{{ old('title', $lesson->title ?? '') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                               placeholder="Ex: Introduction à la programmation">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                        <select name="type" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="regular" {{ old('type', $lesson->type ?? '') == 'regular' ? 'selected' : '' }}>Cours régulier</option>
                            <option value="revision" {{ old('type', $lesson->type ?? '') == 'revision' ? 'selected' : '' }}>Révision</option>
                            <option value="practical" {{ old('type', $lesson->type ?? '') == 'practical' ? 'selected' : '' }}>Travaux pratiques</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Classe *</label>
                        <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id', $lesson->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                    {{ $class->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Matière *</label>
                        <select name="subject_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une matière</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id', $lesson->subject_id ?? '') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Professeur *</label>
                        <select name="teacher_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner un professeur</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" {{ old('teacher_id', $lesson->teacher_id ?? '') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                        <input type="date" name="lesson_date" required value="{{ old('lesson_date', isset($lesson) ? $lesson->lesson_date->format('Y-m-d') : '') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div>

                    <!-- <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Heure début</label>
                        <input type="time" name="start_time" value="{{ old('start_time', isset($lesson) && $lesson->start_time ? $lesson->start_time->format('H:i') : '') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Heure fin</label>
                        <input type="time" name="end_time" value="{{ old('end_time', isset($lesson) && $lesson->end_time ? $lesson->end_time->format('H:i') : '') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    </div> -->
                </div>
            </div>

            <!-- Description et contenu -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Description et contenu
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                        <textarea name="description" rows="3"
                                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description', $lesson->description ?? '') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Contenu</label>
                        <textarea name="content" rows="6"
                                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('content', $lesson->content ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Support de cours, notes, etc.</p>
                    </div>
                </div>
            </div>

            <!-- Objectifs et ressources -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Objectifs et ressources
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Objectifs pédagogiques</label>
                        <div id="objectives-container">
                            @php
                                $objectives = old('objectives', isset($lesson) && $lesson->objectives ? json_decode($lesson->objectives, true) : []);
                            @endphp
                            @if(!empty($objectives))
                                @foreach($objectives as $index => $objective)
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="objectives[]" value="{{ $objective }}"
                                               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white"
                                               placeholder="Objectif {{ $index + 1 }}">
                                        <button type="button" class="remove-objective text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-objective" class="mt-2 text-sm text-primary-400 hover:text-primary-300">
                            + Ajouter un objectif
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Ressources</label>
                        <div id="resources-container">
                            @php
                                $resources = old('resources', isset($lesson) && $lesson->resources ? json_decode($lesson->resources, true) : []);
                            @endphp
                            @if(!empty($resources))
                                @foreach($resources as $index => $resource)
                                    <div class="flex gap-2 mb-2">
                                        <input type="text" name="resources[]" value="{{ $resource }}"
                                               class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white"
                                               placeholder="URL ou chemin de la ressource">
                                        <button type="button" class="remove-resource text-red-400 hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" id="add-resource" class="mt-2 text-sm text-primary-400 hover:text-primary-300">
                            + Ajouter une ressource
                        </button>
                    </div>
                </div>
            </div>

            @if(isset($lesson))
            <!-- Statut -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Statut
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Statut *</label>
                    <select name="status" required class="w-full md:w-1/3 bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        <option value="scheduled" {{ old('status', $lesson->status ?? '') == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                        <option value="ongoing" {{ old('status', $lesson->status ?? '') == 'ongoing' ? 'selected' : '' }}>En cours</option>
                        <option value="completed" {{ old('status', $lesson->status ?? '') == 'completed' ? 'selected' : '' }}>Terminée</option>
                        <option value="cancelled" {{ old('status', $lesson->status ?? '') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
            </div>
            @endif

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('lessons.index') }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    {{ isset($lesson) ? 'Mettre à jour' : 'Créer la leçon' }}
                </button>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des objectifs
    const objectivesContainer = document.getElementById('objectives-container');
    const addObjectiveBtn = document.getElementById('add-objective');
    
    addObjectiveBtn.addEventListener('click', function() {
        const index = objectivesContainer.children.length;
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';
        div.innerHTML = `
            <input type="text" name="objectives[]" class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="Objectif ${index + 1}">
            <button type="button" class="remove-objective text-red-400 hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        objectivesContainer.appendChild(div);
    });
    
    // Gestion des ressources
    const resourcesContainer = document.getElementById('resources-container');
    const addResourceBtn = document.getElementById('add-resource');
    
    addResourceBtn.addEventListener('click', function() {
        const index = resourcesContainer.children.length;
        const div = document.createElement('div');
        div.className = 'flex gap-2 mb-2';
        div.innerHTML = `
            <input type="text" name="resources[]" class="flex-1 bg-gray-800 border border-gray-700 rounded-lg px-4 py-2 text-white" placeholder="URL ou chemin de la ressource">
            <button type="button" class="remove-resource text-red-400 hover:text-red-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        `;
        resourcesContainer.appendChild(div);
    });
    
    // Suppression dynamique
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-objective')) {
            e.target.closest('.flex').remove();
        }
        if (e.target.closest('.remove-resource')) {
            e.target.closest('.flex').remove();
        }
    });
});
</script>
@endpush
@endsection