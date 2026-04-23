<!-- resources/views/tenant/subjects/show.blade.php -->
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
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $subject->name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">Détails de la matière</p>
                    </div>
                </div>
            </div>
            
            <!-- Breadcrumb & Actions -->
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="{{ route('subjects.index', [
                                        'tenant' => tenant()->name
                                    ]) }}" class="hover:text-gray-300 transition-colors">Matières</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">{{ Str::limit($subject->name, 20) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('subjects.edit', [
                                        'tenant' => tenant()->name,
                                        'subject' => $subject->id
                                    ]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('subjects.index', [
                                        'tenant' => tenant()->name
                                    ]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Subject Details Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                            <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                            Informations générales
                        </h2>
                        <span class="text-xs px-2 py-1 bg-gray-850 rounded-full text-gray-300">Code: {{ $subject->code }}</span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nom complet</label>
                                <p class="mt-1 text-lg font-medium text-white">{{ $subject->name }}</p>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Description</label>
                                @if($subject->description)
                                <p class="mt-1 text-gray-300 whitespace-pre-line">{{ $subject->description }}</p>
                                @else
                                <p class="mt-1 text-gray-500 italic">Aucune description</p>
                                @endif
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Niveau scolaire</label>
                                @if($subject->level)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600/10 text-blue-400 border border-blue-600/20">
                                        {{ $subject->level_label }}
                                    </span>
                                </div>
                                @else
                                <p class="mt-1 text-gray-500">Non spécifié</p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Heures/Sem</label>
                                    <p class="mt-1 text-2xl font-bold text-white">{{ $subject->hours_per_week }}</p>
                                    <p class="text-xs text-gray-500 mt-1">heures par semaine</p>
                                </div>
                                
                                <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                    <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Coefficient</label>
                                    <p class="mt-1 text-2xl font-bold text-purple-400">{{ $subject->formatted_coefficient }}</p>
                                    <p class="text-xs text-gray-500 mt-1">poids dans la moyenne</p>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</label>
                                <div class="mt-2 flex items-center gap-3">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $subject->is_active ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 {{ $subject->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    </span>
                                    <span class="text-sm font-medium {{ $subject->is_active ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $subject->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</label>
                                <div class="mt-2 space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Créée le:</span>
                                        <span class="text-white">{{ $subject->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Modifiée le:</span>
                                        <span class="text-white">{{ $subject->updated_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Teachers & Classes Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Teachers Card -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden card-hover">
                    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-white flex items-center gap-3">
                                <div class="w-2 h-5 bg-success rounded-full"></div>
                                Professeurs assignés
                            </h3>
                            <span class="text-xs px-2 py-1 bg-success/10 text-success rounded-full">
                                {{ $subject->teachers->count() }} prof(s)
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($subject->teachers->count() > 0)
                        <div class="space-y-3">
                            @foreach($subject->teachers as $teacher)
                            <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-success/10 rounded-full flex items-center justify-center">
                                        <span class="text-success text-sm">👨‍🏫</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $teacher->first_name }} {{ $teacher->last_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $teacher->email }}</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 bg-gray-800 rounded-full text-gray-400">
                                    ID: {{ $teacher->id }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-3">
                                <svg class="w-12 h-12 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400">Aucun professeur assigné</p>
                            <p class="text-sm text-gray-500 mt-1">Les affectations se font dans un module dédié</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Classes Card -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden card-hover">
                    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-white flex items-center gap-3">
                                <div class="w-2 h-5 bg-warning rounded-full"></div>
                                Classes concernées
                            </h3>
                            <span class="text-xs px-2 py-1 bg-warning/10 text-warning rounded-full">
                                {{ $subject->classes->count() }} classe(s)
                            </span>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($subject->classes->count() > 0)
                        <div class="space-y-3">
                            @foreach($subject->classes as $class)
                            <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg border border-gray-700">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-warning/10 rounded-full flex items-center justify-center">
                                        <span class="text-warning text-sm">🏫</span>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-white">{{ $class->name }}</p>
                                        <p class="text-xs text-gray-500">Année: {{ $class->year->name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <span class="text-xs px-2 py-1 bg-gray-800 rounded-full text-gray-400">
                                    ID: {{ $class->id }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="text-center py-8">
                            <div class="text-gray-500 mb-3">
                                <svg class="w-12 h-12 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <p class="text-gray-400">Aucune classe assignée</p>
                            <p class="text-sm text-gray-500 mt-1">Les affectations se font dans un module dédié</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-info rounded-full"></div>
                    Statistiques
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Total d'heures par an</div>
                        <div class="text-lg font-bold text-white">{{ $subject->hours_per_week * 36 }}</div>
                        <div class="text-xs text-gray-500 mt-1">sur 36 semaines scolaires</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Poids dans les moyennes</div>
                        <div class="text-lg font-bold text-purple-400">{{ $subject->coefficient }}x</div>
                        <div class="text-xs text-gray-500 mt-1">coefficient appliqué</div>
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-warning rounded-full"></div>
                    Actions rapides
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('subjects.edit', [
                                        'tenant' => tenant()->name,
                                        'subject' => $subject->id
                                    ]) }}"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-primary-600/10 hover:bg-primary-600/20 text-primary-400 border border-primary-600/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier la matière
                    </a>
                    
                    <form action="{{ route('subjects.toggle-active', ['subject' => $subject->id]) }}" method="POST">
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
                    
                    <a href="{{ route('subjects.index', [
                                        'tenant' => tenant()->name
                                    ]) }}"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour à la liste
                    </a>
                </div>
            </div>
            
            <!-- Metadata (if exists) -->
            @if($subject->metadata)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-purple-500 rounded-full"></div>
                    Métadonnées
                </h3>
                <div class="space-y-2">
                    @foreach($subject->metadata as $key => $value)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ $key }}:</span>
                        <span class="text-white">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection