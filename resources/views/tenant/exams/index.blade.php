{{-- resources/views/tenant/exams/index.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Examens</h1>
                <p class="text-gray-400 text-sm mt-1">Gestion des examens, contrôles et évaluations</p>
            </div>
            <a href="{{ route('exams.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvel examen
            </a>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <label class="block text-sm font-medium text-gray-300 mb-2">Type</label>
                <select name="type" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous</option>
                    <option value="trimester" {{ request('type') == 'trimester' ? 'selected' : '' }}>Trimestriel</option>
                    <option value="semester" {{ request('type') == 'semester' ? 'selected' : '' }}>Semestriel</option>
                    <option value="final" {{ request('type') == 'final' ? 'selected' : '' }}>Final</option>
                    <option value="quiz" {{ request('type') == 'quiz' ? 'selected' : '' }}>Quiz</option>
                    <option value="test" {{ request('type') == 'test' ? 'selected' : '' }}>Test</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                <select name="status" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous</option>
                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Planifié</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>En cours</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
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

    <!-- Liste des examens -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($exams as $exam)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-all">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-white">{{ $exam->title }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($exam->type == 'trimester') bg-blue-900/50 text-blue-400
                            @elseif($exam->type == 'semester') bg-purple-900/50 text-purple-400
                            @elseif($exam->type == 'final') bg-red-900/50 text-red-400
                            @elseif($exam->type == 'quiz') bg-green-900/50 text-green-400
                            @else bg-yellow-900/50 text-yellow-400
                            @endif">
                            @if($exam->type == 'trimester') Trimestriel
                            @elseif($exam->type == 'semester') Semestriel
                            @elseif($exam->type == 'final') Final
                            @elseif($exam->type == 'quiz') Quiz
                            @else Test
                            @endif
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($exam->status == 'scheduled') bg-yellow-900/50 text-yellow-400
                            @elseif($exam->status == 'ongoing') bg-blue-900/50 text-blue-400
                            @elseif($exam->status == 'completed') bg-green-900/50 text-green-400
                            @else bg-red-900/50 text-red-400
                            @endif">
                            @if($exam->status == 'scheduled') Planifié
                            @elseif($exam->status == 'ongoing') En cours
                            @elseif($exam->status == 'completed') Terminé
                            @else Annulé
                            @endif
                        </span>
                    </div>
                    
                    @if($exam->description)
                    <p class="text-gray-400 text-sm mb-3">{{ Str::limit($exam->description, 150) }}</p>
                    @endif
                    
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $exam->exam_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ date('H:i', strtotime($exam->start_time)) }} - {{ date('H:i', strtotime($exam->end_time)) }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>{{ $exam->class->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Coef: {{ $exam->coefficient }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span>{{ $exam->max_score }}/{{ $exam->max_score }}</span>
                        </div>
                    </div>
                    
                    @if($exam->results_count > 0)
                    <div class="mt-3 text-sm text-primary-400">
                        {{ $exam->results_count }} résultat(s) enregistré(s)
                    </div>
                    @endif
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('exams.show',$exam->id) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('exams.edit', $exam->id) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    <a href="{{ route('exams.results', $exam->id) }}"
                       class="p-2 text-green-400 hover:text-green-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </a>
                    <form action="{{ route('exams.destroy', $exam->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-300 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet examen ?')">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">Aucun examen</h3>
            <p class="text-gray-400">Commencez par créer un nouvel examen</p>
        </div>
        @endforelse
    </div>
    
    @if($exams->hasPages())
    <div class="mt-6">
        {{ $exams->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection