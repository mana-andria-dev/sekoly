{{-- resources/views/tenant/lessons/show.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $lesson->title }}</h1>
                <p class="text-gray-400 text-sm mt-1">Détails de la leçon</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('lessons.index', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour à la liste
                </a>
                <a href="{{ route('lessons.edit', [app('tenant')->name, $lesson->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Colonne gauche - Informations principales -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Description -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Description
                </h2>
                <p class="text-gray-300 leading-relaxed">
                    {{ $lesson->description ?? 'Aucune description disponible.' }}
                </p>
            </div>

            <!-- Contenu -->
            @if($lesson->content)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Contenu du cours
                </h2>
                <div class="prose prose-invert max-w-none">
                    {!! nl2br(e($lesson->content)) !!}
                </div>
            </div>
            @endif

            <!-- Objectifs pédagogiques -->
            @php
                // Récupérer les objectifs (déjà décodés par le cast)
                $objectives = $lesson->objectives;
                if (is_string($objectives)) {
                    $objectives = json_decode($objectives, true);
                }
                if (empty($objectives)) {
                    $objectives = [];
                }
            @endphp
            
            @if(count($objectives) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Objectifs pédagogiques
                </h2>
                <ul class="space-y-2">
                    @foreach($objectives as $objective)
                    <li class="flex items-start gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-green-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>{{ $objective }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Ressources -->
            @php
                // Récupérer les ressources (déjà décodées par le cast)
                $resources = $lesson->resources;
                if (is_string($resources)) {
                    $resources = json_decode($resources, true);
                }
                if (empty($resources)) {
                    $resources = [];
                }
            @endphp
            
            @if(count($resources) > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-3">
                    <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
                    Ressources
                </h2>
                <div class="space-y-2">
                    @foreach($resources as $resource)
                    <a href="{{ $resource }}" target="_blank" class="flex items-center gap-3 p-3 bg-gray-800 rounded-lg hover:bg-gray-750 transition-colors group">
                        <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102m3.172-3.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.102"/>
                        </svg>
                        <span class="text-gray-300 group-hover:text-white">{{ $resource }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Colonne droite - Informations complémentaires -->
        <div class="space-y-6">
            <!-- Statut et type -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Statut</h2>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Statut</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($lesson->status == 'scheduled') bg-yellow-900/50 text-yellow-400
                            @elseif($lesson->status == 'ongoing') bg-blue-900/50 text-blue-400
                            @elseif($lesson->status == 'completed') bg-green-900/50 text-green-400
                            @else bg-red-900/50 text-red-400
                            @endif">
                            {{ ucfirst($lesson->status) }}
                        </span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-400">Type</span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($lesson->type == 'regular') bg-blue-900/50 text-blue-400
                            @elseif($lesson->type == 'revision') bg-green-900/50 text-green-400
                            @else bg-purple-900/50 text-purple-400
                            @endif">
                            @if($lesson->type == 'regular') Cours régulier
                            @elseif($lesson->type == 'revision') Révision
                            @else Travaux pratiques
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Détails de la leçon -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Détails</h2>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Date</div>
                            <div class="font-medium">{{ $lesson->lesson_date instanceof \Carbon\Carbon ? $lesson->lesson_date->format('d/m/Y') : date('d/m/Y', strtotime($lesson->lesson_date)) }}</div>
                        </div>
                    </div>
                    
                    @if($lesson->start_time && $lesson->end_time)
                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Horaire</div>
                            <div class="font-medium">
                                {{ $lesson->start_time instanceof \Carbon\Carbon ? $lesson->start_time->format('H:i') : date('H:i', strtotime($lesson->start_time)) }} - 
                                {{ $lesson->end_time instanceof \Carbon\Carbon ? $lesson->end_time->format('H:i') : date('H:i', strtotime($lesson->end_time)) }}
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Classe</div>
                            <div class="font-medium">{{ $lesson->class->name ?? 'Non définie' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Matière</div>
                            <div class="font-medium">{{ $lesson->subject->name ?? 'Non définie' }}</div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-gray-300">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <div>
                            <div class="text-sm text-gray-400">Professeur</div>
                            <div class="font-medium">{{ $lesson->teacher->full_name ?? 'Non défini' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations de création -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Informations</h2>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Créé le</span>
                        <span class="text-gray-300">{{ $lesson->created_at instanceof \Carbon\Carbon ? $lesson->created_at->format('d/m/Y à H:i') : date('d/m/Y à H:i', strtotime($lesson->created_at)) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Dernière modification</span>
                        <span class="text-gray-300">{{ $lesson->updated_at instanceof \Carbon\Carbon ? $lesson->updated_at->format('d/m/Y à H:i') : date('d/m/Y à H:i', strtotime($lesson->updated_at)) }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions rapides -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-4">Actions rapides</h2>
                <div class="space-y-2">
                    <form action="{{ route('lessons.status', [app('tenant')->name, $lesson->id]) }}" method="POST" class="inline-block w-full">
                        @csrf
                        @method('PATCH')
                        <select name="status" onchange="this.form.submit()" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            <option value="scheduled" {{ $lesson->status == 'scheduled' ? 'selected' : '' }}>Planifiée</option>
                            <option value="ongoing" {{ $lesson->status == 'ongoing' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ $lesson->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ $lesson->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </form>
                    
                    <button onclick="window.print()" class="w-full mt-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-colors">
                        Imprimer
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
@media print {
    .sidebar-link, .sidebar-icon, .bg-gray-900, .border, .bg-primary-600, .bg-gray-800, button, a {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .print\:block {
        display: block !important;
    }
    
    .text-white {
        color: black !important;
    }
    
    .text-gray-300, .text-gray-400 {
        color: #666 !important;
    }
    
    .bg-gray-900 {
        background: white !important;
        border: 1px solid #ddd !important;
    }
}
</style>
@endpush