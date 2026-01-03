<!-- resources/views/tenant/subjects/create.blade.php -->
@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">📚</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouvelle Matière</h1>
                        <p class="text-gray-400 text-sm mt-1">Ajouter une nouvelle matière académique</p>
                    </div>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="/subjects" class="hover:text-gray-300 transition-colors">Matières</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">Nouvelle</span>
                </div>
                <a href="/subjects"
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
        <!-- Main Form (2/3 width) -->
        <div class="lg:col-span-2">
            <form action="/subjects" method="POST" class="space-y-6">
                @csrf
                
                <!-- Form Card -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                                <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                                Informations de la matière
                            </h2>
                            <span class="text-xs text-gray-500 px-3 py-1 bg-gray-850 rounded-full">Tous les champs sont requis</span>
                        </div>
                    </div>
                    
                    <!-- Form Body -->
                    <div class="p-6 space-y-6">
                        <!-- Code -->
                        <div class="animate-slide-in" style="animation-delay: 0.1s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                                    Code matière
                                </label>
                                <span class="text-xs text-gray-500">Laisser vide pour générer automatiquement</span>
                            </div>
                            <input type="text" 
                                   name="code" 
                                   value="{{ old('code') }}"
                                   placeholder="Ex: MATH-001"
                                   class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">
                            @error('code')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Name -->
                        <div class="animate-slide-in" style="animation-delay: 0.2s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-success rounded-full"></span>
                                    Nom de la matière
                                </label>
                                <span class="text-xs text-gray-500">Ex: Mathématiques, Français, Physique</span>
                            </div>
                            <input type="text" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="Ex: Mathématiques"
                                   required
                                   class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">
                            @error('name')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Description -->
                        <div class="animate-slide-in" style="animation-delay: 0.3s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-warning rounded-full"></span>
                                    Description
                                </label>
                                <span class="text-xs text-gray-500">Optionnel</span>
                            </div>
                            <textarea name="description" 
                                      rows="3"
                                      placeholder="Décrivez brièvement cette matière..."
                                      class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Level -->
                        <div class="animate-slide-in" style="animation-delay: 0.4s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-info rounded-full"></span>
                                    Niveau scolaire
                                </label>
                                <span class="text-xs text-gray-500">Sélectionnez le niveau concerné</span>
                            </div>
                            <div class="relative">
                                <select name="level"
                                        class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200 appearance-none">
                                    <option value="">Non spécifié</option>
                                    <option value="maternelle" {{ old('level') == 'maternelle' ? 'selected' : '' }}>Maternelle</option>
                                    <option value="primaire" {{ old('level') == 'primaire' ? 'selected' : '' }}>Primaire</option>
                                    <option value="college" {{ old('level') == 'college' ? 'selected' : '' }}>Collège</option> <!-- Notez "college" sans 'e' -->
                                    <option value="lycee" {{ old('level') == 'lycee' ? 'selected' : '' }}>Lycée</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('level')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Hours per week & Coefficient -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="animate-slide-in" style="animation-delay: 0.5s">
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
                            
                            <div class="animate-slide-in" style="animation-delay: 0.6s">
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
                        <div class="animate-slide-in" style="animation-delay: 0.7s">
                            <div class="flex items-center justify-between p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-success/10 rounded-lg">
                                        <span class="text-success">✅</span>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-300">Statut de la matière</label>
                                        <p class="text-xs text-gray-500 mt-1">Activez ou désactivez cette matière</p>
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
                            <a href="/subjects"
                               class="px-5 py-2.5 border border-gray-700 text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 rounded-lg font-medium transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Créer la matière
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Stats Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-success rounded-full"></div>
                        Statistiques actuelles
                    </h3>
                    <span class="text-xs px-2 py-1 bg-success/10 text-success rounded-full">Live</span>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-success/10 rounded-lg">
                                <span class="text-success">📚</span>
                            </div>
                            <span class="text-sm text-gray-300">Matières totales</span>
                        </div>
                        <span class="font-bold text-white text-lg">{{ \App\Models\Subject::forTenant()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-primary-600/10 rounded-lg">
                                <span class="text-primary-600">✅</span>
                            </div>
                            <span class="text-sm text-gray-300">Matières actives</span>
                        </div>
                        <span class="font-bold text-white text-lg">{{ \App\Models\Subject::forTenant()->active()->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-warning/10 rounded-lg">
                                <span class="text-warning">👨‍🏫</span>
                            </div>
                            <span class="text-sm text-gray-300">Matières assignées</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="w-20 bg-gray-800 rounded-full h-2">
                                <div class="bg-success h-2 rounded-full" style="width: 45%"></div>
                            </div>
                            <span class="font-bold text-success text-sm">45%</span>
                        </div>
                    </div>
                </div>
            </div>
            
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
                        <span class="text-sm text-gray-400">Le code est unique et peut être généré automatiquement si laissé vide</span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-success/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-success text-xs font-bold">2</span>
                        </div>
                        <span class="text-sm text-gray-400">Le coefficient influence le poids de la matière dans les moyennes</span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-warning/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-warning text-xs font-bold">3</span>
                        </div>
                        <span class="text-sm text-gray-400">Les matières inactives ne seront pas disponibles pour les affectations</span>
                    </li>
                </ul>
            </div>
            
            <!-- Quick Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-info rounded-full"></div>
                    Actions rapides
                </h3>
                <div class="space-y-3">
                    <a href="/subjects"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-primary-600/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-primary-600">📋</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir toutes les matières</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="#"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-success/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-success">👨‍🏫</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Gérer les professeurs</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    <a href="/classes"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-warning/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-warning">🏫</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir les classes</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection