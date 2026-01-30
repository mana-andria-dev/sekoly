<!-- resources/views/tenant/subjects/edit.blade.php -->
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
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Modifier la Matière</h1>
                        <p class="text-gray-400 text-sm mt-1">Modifier les informations de la matière</p>
                    </div>
                </div>
            </div>
            
            <!-- Breadcrumb -->
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="{{ route('subjects.index', [
                                    'tenant' => app('tenant')->name
                                ]) }}" class="hover:text-gray-300 transition-colors">Matières</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">{{ $subject->name }}</span>
                </div>
                <a href="{{ route('subjects.index', [
                            'tenant' => app('tenant')->name
                        ]) }}"
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
            <form action="{{ route('subjects.update', [
                            'tenant' => app('tenant')->name,
                            'subject' => $subject->id
                        ]) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <!-- Form Card -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                    <!-- Card Header -->
                    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                        <div class="flex items-center justify-between">
                            <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                                <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                                Informations de la matière
                            </h2>
                            <span class="text-xs px-2 py-1 bg-gray-850 rounded-full text-gray-300">Code: {{ $subject->code }}</span>
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
                                <span class="text-xs text-gray-500">Identifiant unique</span>
                            </div>
                            <input type="text" 
                                   name="code" 
                                   value="{{ old('code', $subject->code) }}"
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
                                   value="{{ old('name', $subject->name) }}"
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
                                      class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white placeholder-gray-500 focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200">{{ old('description', $subject->description) }}</textarea>
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
                                    @foreach($levels as $value => $label)
                                        <option value="{{ $value }}" @selected(old('level', $subject->level) == $value) 
                                                class="bg-gray-900 py-2">
                                            {{ $label }}
                                        </option>
                                    @endforeach
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
                                           value="{{ old('hours_per_week', $subject->hours_per_week) }}"
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
                                           value="{{ old('coefficient', $subject->coefficient) }}"
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
                                    <div class="p-2 {{ $subject->is_active ? 'bg-success/10' : 'bg-danger/10' }} rounded-lg">
                                        <span class="{{ $subject->is_active ? 'text-success' : 'text-danger' }}">✅</span>
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
                                           {{ old('is_active', $subject->is_active) ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-success"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="px-6 py-5 border-t border-gray-800 bg-gray-900/50">
                        <div class="flex justify-end gap-3">

                            <a href="{{ route('subjects.index', [
                                    'tenant' => app('tenant')->name,
                                ]) }}"
                               class="px-5 py-2.5 border border-gray-700 text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 rounded-lg font-medium transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit"
                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        
        <!-- Sidebar Information -->
        <div class="space-y-6">
            <!-- Subject Info Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-info rounded-full"></div>
                        Informations actuelles
                    </h3>
                    <span class="text-xs px-2 py-1 bg-info/10 text-info rounded-full">ID: {{ $subject->id }}</span>
                </div>
                <div class="space-y-4">
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Créée le</div>
                        <div class="text-sm text-white">{{ $subject->created_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Dernière modification</div>
                        <div class="text-sm text-white">{{ $subject->updated_at->format('d/m/Y à H:i') }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Statut actuel</div>
                        <div class="text-sm {{ $subject->is_active ? 'text-green-400' : 'text-red-400' }}">
                            {{ $subject->is_active ? 'Active' : 'Inactive' }}
                        </div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Affectations</div>
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-white">{{ $subject->teachers()->count() }} prof(s)</div>
                            <div class="text-sm text-white">{{ $subject->classes()->count() }} classe(s)</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Actions Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-warning rounded-full"></div>
                    Actions
                </h3>
                <div class="space-y-3">
                   
                    <a href="{{ route('subjects.show', [
                            'tenant' => app('tenant')->name,
                            'subject' => $subject->id
                        ]) }} "
                       class="w-full flex items-center justify-center gap-2 p-3 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 border border-blue-600/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Voir les détails
                    </a>
                    
                    <form action="{{ route('subjects.toggle-active', [
                                    'tenant' => app('tenant')->name,
                                    'subject' => $subject->id
                                ]) }}" method="POST" class="mb-4">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous sûr de vouloir changer le statut de cette matière ?')"
                                class="w-full flex items-center justify-center gap-2 p-3 {{ $subject->is_active ? 'bg-danger/10 hover:bg-danger/20 text-danger' : 'bg-success/10 hover:bg-success/20 text-success' }} border border-current/20 rounded-lg transition-all duration-200">
                            @if($subject->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Désactiver la matière
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activer la matière
                            @endif
                        </button>
                    </form>
                    
                    <form action="{{ route('subjects.destroy', [
                                        'tenant' => app('tenant')->name,
                                        'subject' => $subject->id
                                    ]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette matière ? Cette action est irréversible.')"
                                class="w-full flex items-center justify-center gap-2 p-3 bg-danger/10 hover:bg-danger/20 text-danger border border-danger/20 rounded-lg transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer cette matière
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection