{{-- resources/views/tenant/grades/create.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Ajouter une note</h1>
                <p class="text-gray-400 text-sm mt-1">Saisie d'une note continue pour un élève</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('grades.index', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <a href="#"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    Saisie groupée
                </a>
            </div>
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

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('grades.store', app('tenant')->name) }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Classe *</label>
                    <select name="class_id" id="class_id" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Matière *</label>
                    <select name="subject_id" id="subject_id" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                        <option value="">Sélectionner une matière</option>
                        @foreach($subjects as $subject)
                            <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                {{ $subject->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Élève *</label>
                    <select name="student_id" id="student_id" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                        <option value="">Sélectionner un élève</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type de note *</label>
                    <select name="grade_type" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                        <option value="homework" {{ old('grade_type') == 'homework' ? 'selected' : '' }}>Devoir</option>
                        <option value="test" {{ old('grade_type') == 'test' ? 'selected' : '' }}>Test</option>
                        <option value="quiz" {{ old('grade_type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                        <option value="participation" {{ old('grade_type') == 'participation' ? 'selected' : '' }}>Participation</option>
                        <option value="project" {{ old('grade_type') == 'project' ? 'selected' : '' }}>Projet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Titre</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600"
                           placeholder="Ex: Devoir sur les fractions">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                    <input type="date" name="grade_date" required value="{{ old('grade_date', date('Y-m-d')) }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Note obtenue *</label>
                    <input type="number" name="score" required value="{{ old('score') }}" step="0.01" min="0"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600"
                           placeholder="Ex: 15.5">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Note maximale *</label>
                    <input type="number" name="max_score" required value="{{ old('max_score', 20) }}" step="0.5" min="1"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Coefficient *</label>
                    <input type="number" name="coefficient" required value="{{ old('coefficient', 1) }}" step="0.5" min="0.5" max="5"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
                    <select name="period" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">
                        <option value="">Non spécifiée</option>
                        <option value="trimester1" {{ old('period') == 'trimester1' ? 'selected' : '' }}>1er Trimestre</option>
                        <option value="trimester2" {{ old('period') == 'trimester2' ? 'selected' : '' }}>2ème Trimestre</option>
                        <option value="trimester3" {{ old('period') == 'trimester3' ? 'selected' : '' }}>3ème Trimestre</option>
                        <option value="annual" {{ old('period') == 'annual' ? 'selected' : '' }}>Annuel</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commentaire</label>
                    <textarea name="comment" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600"
                              placeholder="Commentaire sur la note...">{{ old('comment') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('grades.index', app('tenant')->name) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Enregistrer la note
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const classSelect = document.getElementById('class_id');
    const studentSelect = document.getElementById('student_id');
    
    async function loadStudents(classId) {
        if (!classId) {
            studentSelect.innerHTML = '<option value="">Sélectionner un élève</option>';
            return;
        }
        
        studentSelect.innerHTML = '<option value="">Chargement...</option>';
        
        try {
            const response = await fetch(`/api/classes/${classId}/students`);
            const students = await response.json();
            
            studentSelect.innerHTML = '<option value="">Sélectionner un élève</option>';
            students.forEach(student => {
                const option = document.createElement('option');
                option.value = student.id;
                option.textContent = `${student.first_name} ${student.last_name}`;
                studentSelect.appendChild(option);
            });
        } catch (error) {
            console.error('Erreur:', error);
            studentSelect.innerHTML = '<option value="">Erreur de chargement</option>';
        }
    }
    
    classSelect.addEventListener('change', function() {
        loadStudents(this.value);
    });
    
    // Charger les élèves si une classe est déjà sélectionnée
    if (classSelect.value) {
        loadStudents(classSelect.value);
    }
});
</script>
@endpush
@endsection