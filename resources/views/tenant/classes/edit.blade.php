<!-- pages/classes/edit.blade.php -->
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
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Modifier la Classe</h1>
                        <p class="text-gray-400 text-sm mt-1">Modifier les informations de la classe</p>
                    </div>
                </div>
            </div>
            
            <!-- Breadcrumb & Actions -->
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="/classes" class="hover:text-gray-300 transition-colors">Classes</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">Modifier</span>
                </div>
                <a href="{{ route('classes.index', [
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
            <form action="{{ route('classes.update', [
                                            'tenant' => app('tenant')->name,
                                            'schoolClass' => $schoolClass->id
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
                                Informations de la classe
                            </h2>
                            <span class="text-xs text-gray-500 px-3 py-1 bg-gray-850 rounded-full">Tous les champs sont requis</span>
                        </div>
                    </div>
                    
                    <!-- Form Body -->
                    <div class="p-6 space-y-6">
                        <!-- Class Name -->
                        <div class="animate-slide-in" style="animation-delay: 0.1s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                                    Nom de la classe
                                </label>
                                <span class="text-xs text-gray-500">Ex: Terminale S, CM2 A, 6ème B</span>
                            </div>
                            <input type="text" name="name" value="{{ old('name', $schoolClass->name) }}"
                                   placeholder="Ex: Terminale S - Scientifique"
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
                        
                        <!-- School Year -->
                        <div class="animate-slide-in" style="animation-delay: 0.3s">
                            <div class="flex items-center justify-between mb-2">
                                <label class="text-sm font-medium text-gray-300 flex items-center gap-2">
                                    <span class="w-2 h-2 bg-info rounded-full"></span>
                                    Année scolaire
                                </label>
                                <span class="text-xs text-gray-500">Sélectionnez une année</span>
                            </div>
                            <div class="relative">
                                <select name="school_year_id"
                                        class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-3.5 text-white focus:outline-none focus:border-primary-600 focus:ring-2 focus:ring-primary-600/20 transition-all duration-200 appearance-none">
                                    <option value="" class="bg-gray-900 py-2">Sélectionnez une année scolaire</option>
                                    @foreach($schoolYears as $year)
                                        <option value="{{ $year->id }}" @selected(old('school_year_id', $schoolClass->school_year_id) == $year->id) 
                                                class="bg-gray-900 py-2">
                                            {{ $year->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </div>
                            </div>
                            @error('school_year_id')
                                <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Card Footer -->
                    <div class="px-6 py-5 border-t border-gray-800 bg-gray-900/50">
                        <div class="flex justify-end gap-3">
                            <a href="{{ route('classes.index', [
                                    'tenant' => app('tenant')->name
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
            <!-- Class Info Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-info rounded-full"></div>
                        Informations actuelles
                    </h3>
                    <span class="text-xs px-2 py-1 bg-info/10 text-info rounded-full">ID: {{ $schoolClass->id }}</span>
                </div>
                <div class="space-y-4">
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Créée le</div>
                        <div class="text-sm text-white">{{ $schoolClass->created_at->format('d/m/Y') }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Année scolaire</div>
                        <div class="text-sm text-white">{{ $schoolClass->year->name }}</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Élèves inscrits</div>
                        <div class="text-sm text-white">{{ $schoolClass->students()->count() }} élèves</div>
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
                    <form action="{{ route('classes.destroy', [
                            'tenant' => app('tenant')->name,
                            'schoolClass' => $schoolClass->id
                        ]) }}" method="POST" class="mb-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette classe ?')"
                                class="w-full flex items-center justify-center gap-2 p-3 bg-danger/10 hover:bg-danger/20 text-danger border border-danger/20 rounded-lg transition-all duration-200 group">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Supprimer cette classe
                        </button>
                    </form>
                    
                    <a href="{{ route('classes.index', [
                            'tenant' => app('tenant')->name
                        ]) }}"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-primary-600/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-primary-600">📋</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir toutes les classes</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection