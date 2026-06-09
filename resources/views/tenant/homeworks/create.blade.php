@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouveau devoir</h1>
                <p class="text-gray-400 text-sm mt-1">Créez un nouveau devoir, projet ou travail de recherche</p>
            </div>
            <a href="{{ route('homeworks.index') }}"
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

    <form action="{{ route('homeworks.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                        <input type="text" name="title" required value="{{ old('title') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                               placeholder="Ex: Exercices sur les fractions">
                        @error('title')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                        <select name="type" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="homework" {{ old('type') == 'homework' ? 'selected' : '' }}>Devoir</option>
                            <option value="project" {{ old('type') == 'project' ? 'selected' : '' }}>Projet</option>
                            <option value="research" {{ old('type') == 'research' ? 'selected' : '' }}>Recherche</option>
                        </select>
                        @error('type')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Classe *</label>
                        <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
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
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
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
                                <option value="{{ $teacher->id }}" {{ old('teacher_id') == $teacher->id ? 'selected' : '' }}>
                                    {{ $teacher->full_name }}
                                </option>
                            @endforeach
                        </select>
                        @error('teacher_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Note maximale *</label>
                        <input type="number" name="max_score" required value="{{ old('max_score', 20) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"
                               min="1" max="100">
                        @error('max_score')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Date limite *</label>
                        <input type="date" name="due_date" required value="{{ old('due_date') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('due_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Heure limite</label>
                        <input type="time" name="due_time" value="{{ old('due_time') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        @error('due_time')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Description et instructions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Description et instructions
                </h2>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Description *</label>
                        <textarea name="description" rows="4" required
                                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Instructions</label>
                        <textarea name="instructions" rows="4"
                                  class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">{{ old('instructions') }}</textarea>
                        @error('instructions')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Fichiers joints -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Fichiers joints
                </h2>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Documents</label>
                    <input type="file" name="attachments[]" multiple
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                    <p class="mt-1 text-xs text-gray-500">Vous pouvez joindre plusieurs fichiers (PDF, DOC, etc.)</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('homeworks.index') }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Créer le devoir
                </button>
            </div>
        </div>
    </form>
</div>
@endsection