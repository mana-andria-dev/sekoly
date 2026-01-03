@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouvelle évaluation</h1>
                <p class="text-gray-400 text-sm mt-1">Pour {{ $teacher->full_name }}</p>
            </div>
            <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('teachers.evaluations.store', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <!-- Informations générales -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Informations générales
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Type d'évaluation *</label>
                            <select name="evaluation_type" required class="w-full bg-gray-800 border {{ $errors->has('evaluation_type') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="annual" {{ old('evaluation_type') == 'annual' ? 'selected' : '' }}>Annuelle</option>
                                <option value="probation" {{ old('evaluation_type') == 'probation' ? 'selected' : '' }}>Période d'essai</option>
                                <option value="performance" {{ old('evaluation_type') == 'performance' ? 'selected' : '' }}>Performance</option>
                                <option value="student_feedback" {{ old('evaluation_type') == 'student_feedback' ? 'selected' : '' }}>Retour étudiants</option>
                            </select>
                            @error('evaluation_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date d'évaluation *</label>
                            <input type="date" name="evaluation_date" required value="{{ old('evaluation_date', date('Y-m-d')) }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('evaluation_date') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('evaluation_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Notes -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Notes (sur 10)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Compétences pédagogiques *</label>
                        <input type="number" step="0.1" min="0" max="10" name="pedagogical_skills" required 
                               value="{{ old('pedagogical_skills') }}"
                               class="w-full bg-gray-800 border {{ $errors->has('pedagogical_skills') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white text-center">
                        @error('pedagogical_skills')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Connaissance matière *</label>
                        <input type="number" step="0.1" min="0" max="10" name="subject_knowledge" required 
                               value="{{ old('subject_knowledge') }}"
                               class="w-full bg-gray-800 border {{ $errors->has('subject_knowledge') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white text-center">
                        @error('subject_knowledge')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Gestion classe *</label>
                        <input type="number" step="0.1" min="0" max="10" name="classroom_management" required 
                               value="{{ old('classroom_management') }}"
                               class="w-full bg-gray-800 border {{ $errors->has('classroom_management') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white text-center">
                        @error('classroom_management')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Communication *</label>
                        <input type="number" step="0.1" min="0" max="10" name="communication" required 
                               value="{{ old('communication') }}"
                               class="w-full bg-gray-800 border {{ $errors->has('communication') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white text-center">
                        @error('communication')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-300">Ponctualité *</label>
                        <input type="number" step="0.1" min="0" max="10" name="punctuality" required 
                               value="{{ old('punctuality') }}"
                               class="w-full bg-gray-800 border {{ $errors->has('punctuality') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white text-center">
                        @error('punctuality')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Calcul automatique -->
                <div class="mt-6 pt-6 border-t border-gray-800">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-300">Note globale estimée:</span>
                        <span id="overall-rating" class="text-xl font-bold text-white">0.0/10</span>
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        Calculée automatiquement à partir des notes ci-dessus
                    </div>
                </div>
            </div>
            
            <!-- Commentaires -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Commentaires
                </h2>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Points forts *</label>
                        <textarea name="strengths" rows="3" required
                                  class="w-full bg-gray-800 border {{ $errors->has('strengths') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">{{ old('strengths') }}</textarea>
                        @error('strengths')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Axes d'amélioration</label>
                        <textarea name="improvements_needed" rows="3"
                                  class="w-full bg-gray-800 border {{ $errors->has('improvements_needed') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">{{ old('improvements_needed') }}</textarea>
                        @error('improvements_needed')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Recommandations</label>
                        <textarea name="recommendations" rows="3"
                                  class="w-full bg-gray-800 border {{ $errors->has('recommendations') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">{{ old('recommendations') }}</textarea>
                        @error('recommendations')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Document -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Document
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Document d'évaluation</label>
                        <input type="file" name="document" accept=".pdf,.doc,.docx"
                               class="w-full bg-gray-800 border {{ $errors->has('document') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                        @error('document')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX - Max 5MB</p>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Enregistrer l'évaluation
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Calcul de la note globale
    const ratingInputs = document.querySelectorAll('input[type="number"]');
    const overallRating = document.getElementById('overall-rating');
    
    function calculateOverallRating() {
        let total = 0;
        let count = 0;
        
        ratingInputs.forEach(input => {
            const value = parseFloat(input.value);
            if (!isNaN(value) && value >= 0 && value <= 10) {
                total += value;
                count++;
            }
        });
        
        const average = count > 0 ? total / count : 0;
        overallRating.textContent = average.toFixed(1) + '/10';
        
        // Changer la couleur selon la note
        if (average >= 8) {
            overallRating.className = 'text-xl font-bold text-green-400';
        } else if (average >= 6) {
            overallRating.className = 'text-xl font-bold text-yellow-400';
        } else {
            overallRating.className = 'text-xl font-bold text-red-400';
        }
    }
    
    // Écouter les changements
    ratingInputs.forEach(input => {
        input.addEventListener('input', calculateOverallRating);
        // Validation
        input.addEventListener('change', function() {
            const value = parseFloat(this.value);
            if (value < 0) this.value = 0;
            if (value > 10) this.value = 10;
            calculateOverallRating();
        });
    });
    
    // Calcul initial
    calculateOverallRating();
});
</script>