{{-- resources/views/tenant/school-years/index.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Années scolaires</h1>
                <p class="text-gray-400 text-sm mt-1">Gestion des années scolaires et périodes</p>
            </div>
            <a href="{{ route('school-years.create') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle année scolaire
            </a>
        </div>
    </div>

    <!-- Liste des années scolaires -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($years as $year)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-all">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-white">{{ $year->name }}</h3>
                        @if($year->is_active)
                            <span class="px-2 py-1 text-xs rounded-full bg-green-900/50 text-green-400">
                                Active
                            </span>
                        @else
                            <span class="px-2 py-1 text-xs rounded-full bg-gray-800 text-gray-400">
                                Inactive
                            </span>
                        @endif
                    </div>
                    
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>{{ $year->start_date ? $year->start_date->format('d/m/Y') : 'Non définie' }} - {{ $year->end_date ? $year->end_date->format('d/m/Y') : 'Non définie' }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $year->periodType->name ?? 'Non défini' }} ({{ $year->periodType->period_count ?? 0 }} périodes)</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    @if(!$year->is_active)
                    <form action="{{ route('school-years.activate', $year->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-3 py-1.5 bg-green-600 hover:bg-green-700 rounded-lg text-xs font-medium text-white transition-colors">
                            Activer
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('school-years.edit', $year->id) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                    
                    <form action="{{ route('school-years.destroy', $year->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="p-2 text-red-400 hover:text-red-300 transition-colors"
                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette année scolaire ?')">
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">Aucune année scolaire</h3>
            <p class="text-gray-400">Commencez par créer une nouvelle année scolaire</p>
        </div>
        @endforelse
    </div>
</div>
@endsection