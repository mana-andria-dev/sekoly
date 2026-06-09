{{-- resources/views/tenant/homeworks/index.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Devoirs</h1>
                <p class="text-gray-400 text-sm mt-1">Gestion des devoirs, projets et travaux de recherche</p>
            </div>
            <a href="{{ route('homeworks.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau devoir
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Classe</label>
                <select name="class_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Matière</label>
                <select name="subject_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les matières</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                <select name="status" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expiré</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des devoirs -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($homeworks as $homework)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-all">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-white">{{ $homework->title }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($homework->type == 'homework') bg-blue-900/50 text-blue-400
                            @elseif($homework->type == 'project') bg-purple-900/50 text-purple-400
                            @else bg-green-900/50 text-green-400
                            @endif">
                            @if($homework->type == 'homework') Devoir
                            @elseif($homework->type == 'project') Projet
                            @else Recherche
                            @endif
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($homework->status == 'active') bg-green-900/50 text-green-400
                            @elseif($homework->status == 'expired') bg-red-900/50 text-red-400
                            @else bg-gray-900/50 text-gray-400
                            @endif">
                            {{ ucfirst($homework->status) }}
                        </span>
                    </div>
                    
                    <p class="text-gray-400 text-sm mb-3">{{ Str::limit($homework->description, 150) }}</p>
                    
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>{{ $homework->class->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>{{ $homework->subject->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>À rendre: {{ $homework->due_date->format('d/m/Y') }}
                                @if($homework->due_time) à {{ date('H:i', strtotime($homework->due_time)) }} @endif
                            </span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Note max: {{ $homework->max_score }}/{{ $homework->max_score }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('homeworks.show', $homework->id) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('homeworks.edit', $homework->id) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <form action="{{ route('homeworks.destroy', $homework->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-300 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce devoir ?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">Aucun devoir</h3>
            <p class="text-gray-400">Commencez par créer un nouveau devoir</p>
        </div>
        @endforelse
    </div>
    
    @if($homeworks->hasPages())
    <div class="mt-6">
        {{ $homeworks->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection