@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">🔗</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouvelle Affectation</h1>
                        <p class="text-gray-400 text-sm mt-1">Assigner une matière à une classe avec un professeur</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="/assignments" class="hover:text-gray-300 transition-colors">Affectations</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">Nouvelle</span>
                </div>
                <a href="/assignments"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <form action="/assignments" method="POST" class="space-y-6">
                @csrf
                
                <!-- Form Card -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                                <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                                Informations de l'affectation
                            </h2>
                            <span class="text-xs text-gray-500 px-3 py-1 bg-gray-850 rounded-full">Tous les champs sont requis</span>
                        </div>
                    </div>
                    
                    <!-- Form Body -->
                    <div class="p-6 space-y-6">
                        <!-- Classe -->
                        <div class="animate-slide-in" style="animation-delay: 0.1s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                                    Classe
                                </label>
                                <span class="text-xs text-gray-500">Sélectionnez une classe</span>
                            </div>
                            <div class="relative">
                                <select name="class_id" 
                                        required
                                        class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200 appearance-none">
                                    <option value="">Sélectionnez une classe</option>
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}" 
                                                {{ old('class_id', $prefilled['class_id'] ?? '') == $class->id ? 'selected' : '' }}
                                                class="bg-gray-900 py-2">
                                            {{ $class->name }} - {{ $class->year->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('class_id')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Matière -->
                        <div class="animate-slide-in" style="animation-delay: 0.2s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-success rounded-full"></span>
                                    Matière
                                </label>
                                <span class="text-xs text-gray-500">Sélectionnez une matière</span>
                            </div>
                            <div class="relative">
                                <select name="subject_id" 
                                        required
                                        class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200 appearance-none">
                                    <option value="">Sélectionnez une matière</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" 
                                                {{ old('subject_id', $prefilled['subject_id'] ?? '') == $subject->id ? 'selected' : '' }}
                                                class="bg-gray-900 py-2">
                                            {{ $subject->name }} ({{ $subject->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('subject_id')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Professeur -->
                        <div class="animate-slide-in" style="animation-delay: 0.3s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-warning rounded-full"></span>
                                    Professeur
                                </label>
                                <span class="text-xs text-gray-500">Optionnel - peut être assigné plus tard</span>
                            </div>
                            <div class="relative">
                                <select name="teacher_id" 
                                        class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200 appearance-none">
                                    <option value="">Non assigné pour le moment</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" 
                                                {{ old('teacher_id', $prefilled['teacher_id'] ?? '') == $teacher->id ? 'selected' : '' }}
                                                class="bg-gray-900 py-2">
                                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                                            @if($teacher->email)
                                                ({{ $teacher->email }})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('teacher_id')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Heures par semaine & Coefficient -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="animate-slide-in" style="animation-delay: 0.4s">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                        Heures par semaine
                                    </label>
                                    <span class="text-xs text-gray-500">0-40 heures</span>
                                </div>
                                <div class="relative">
                                    <input type="number" 
                                           name="hours_per_week" 
                                           value="{{ old('hours_per_week', 0) }}"
                                           min="0"
                                           max="40"
                                           step="0.5"
                                           required
                                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 pl-12 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">
                                    <div class="absolute left-0 top-0 bottom-0 flex items-center pl-4 pointer-events-none">
                                        <span class="text-gray-500">⏱️</span>
                                    </div>
                                    <div class="absolute right-0 top-0 bottom-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-gray-500 text-sm">h/sem</span>
                                    </div>
                                </div>
                                @error('hours_per_week')
                                    <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="animate-slide-in" style="animation-delay: 0.5s">
                                <div class="flex items-center justify-between mb-2">
                                    <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                        <span class="w-2 h-2 bg-pink-500 rounded-full"></span>
                                        Coefficient
                                    </label>
                                    <span class="text-xs text-gray-500">0.1 - 10</span>
                                </div>
                                <div class="relative">
                                    <input type="number" 
                                           name="coefficient" 
                                           value="{{ old('coefficient', 1.0) }}"
                                           min="0.1"
                                           max="10"
                                           step="0.1"
                                           required
                                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 pl-12 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">
                                    <div class="absolute left-0 top-0 bottom-0 flex items-center pl-4 pointer-events-none">
                                        <span class="text-gray-500">⚖️</span>
                                    </div>
                                    <div class="absolute right-0 top-0 bottom-0 flex items-center pr-4 pointer-events-none">
                                        <span class="text-gray-500 text-sm">coef.</span>
                                    </div>
                                </div>
                                @error('coefficient')
                                    <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Active Status -->
                        <div class="animate-slide-in" style="animation-delay: 0.6s">
                            <div class="flex items-center justify-between p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-success/10 rounded-lg">
                                        <span class="text-success">✅</span>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-300">Statut de l'affectation</label>
                                        <p class="text-xs text-gray-500 mt-1">Activez ou désactivez cette affectation</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           name="is_active" 
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="px-6 py-5 border-t border-gray-800 bg-gray-900/50">
                        <div class="flex justify-end gap-3">
                            <a href="/assignments"
                               class="px-5 py-2.5 border border-gray-700 text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 rounded-lg font-medium transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer l'affectation
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Guidelines Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-warning rounded-full"></div>
                        Directives importantes
                    </h3>
                </div>
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-primary-600/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 text-xs font-bold">1</span>
                        </div>
                        <span class="text-sm text-gray-400">Une matière ne peut être assignée qu'une seule fois par classe</span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-success/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-success text-xs font-bold">2</span>
                        </div>
                        <span class="text-sm text-gray-400">Le professeur peut être assigné ultérieurement</span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-warning/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-warning text-xs font-bold">3</span>
                        </div>
                        <span class="text-sm text-gray-400">Le coefficient influence le poids dans les moyennes</span>
                    </li>
                </ul>
            </div>
            
            <!-- Quick Stats -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-info rounded-full"></div>
                    Statistiques
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Matières disponibles</div>
                        <div class="text-lg font-bold text-white">{{ $subjects->count() }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Classes disponibles</div>
                        <div class="text-lg font-bold text-white">{{ $classes->count() }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Professeurs disponibles</div>
                        <div class="text-lg font-bold text-white">{{ $teachers->count() }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-purple-500 rounded-full"></div>
                    Actions rapides
                </h3>
                <div class="space-y-3">
                    <a href="/assignments"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-primary-600/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-primary-600">📋</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir toutes les affectations</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="/classes"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-success/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-success">🏫</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir les classes</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-update hours based on selected subject
    const subjectSelect = document.querySelector('select[name="subject_id"]');
    const hoursInput = document.querySelector('input[name="hours_per_week"]');
    
    if (subjectSelect && hoursInput) {
        subjectSelect.addEventListener('change', function() {
            // You can fetch subject data via AJAX here if needed
            // For now, we'll keep it simple
            console.log('Subject changed:', this.value);
        });
    }
});
</script>
@endpush