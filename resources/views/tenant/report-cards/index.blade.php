{{-- resources/views/tenant/report-cards/index.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Bulletins scolaires</h1>
                <p class="text-gray-400 text-sm mt-1">Gestion des bulletins trimestriels et semestriels</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('report-cards.create', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Générer des bulletins
                </a>
            </div>
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
                <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
                <select name="period" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes</option>
                    <option value="trimester1" {{ request('period') == 'trimester1' ? 'selected' : '' }}>1er Trimestre</option>
                    <option value="trimester2" {{ request('period') == 'trimester2' ? 'selected' : '' }}>2ème Trimestre</option>
                    <option value="trimester3" {{ request('period') == 'trimester3' ? 'selected' : '' }}>3ème Trimestre</option>
                    <option value="annual" {{ request('period') == 'annual' ? 'selected' : '' }}>Annuel</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                <select name="status" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Publié</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archivé</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des bulletins -->
    <div class="grid grid-cols-1 gap-4">
        @forelse($reportCards as $reportCard)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 hover:border-gray-700 transition-all">
            <div class="flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h3 class="text-lg font-semibold text-white">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</h3>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($reportCard->period == 'trimester1') bg-blue-900/50 text-blue-400
                            @elseif($reportCard->period == 'trimester2') bg-green-900/50 text-green-400
                            @elseif($reportCard->period == 'trimester3') bg-purple-900/50 text-purple-400
                            @else bg-orange-900/50 text-orange-400
                            @endif">
                            @if($reportCard->period == 'trimester1') 1er Trimestre
                            @elseif($reportCard->period == 'trimester2') 2ème Trimestre
                            @elseif($reportCard->period == 'trimester3') 3ème Trimestre
                            @else Annuel
                            @endif
                        </span>
                        <span class="px-2 py-1 text-xs rounded-full 
                            @if($reportCard->status == 'published') bg-green-900/50 text-green-400
                            @elseif($reportCard->status == 'draft') bg-yellow-900/50 text-yellow-400
                            @else bg-gray-900/50 text-gray-400
                            @endif">
                            {{ ucfirst($reportCard->status) }}
                        </span>
                    </div>
                    
                    <div class="flex flex-wrap gap-4 text-sm text-gray-400">
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <span>{{ $reportCard->class->name }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Moyenne: {{ number_format($reportCard->overall_average ?? 0, 2) }}/20</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                            </svg>
                            <span>Rang: {{ $reportCard->class_rank ?? '-' }}/{{ $reportCard->total_students ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span>Émis le: {{ $reportCard->issued_date->format('d/m/Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-2">
                    <a href="{{ route('report-cards.show', [app('tenant')->name, $reportCard->id]) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </a>
                    <a href="{{ route('report-cards.print', [app('tenant')->name, $reportCard->id]) }}" target="_blank"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                    </a>
                    <a href="{{ route('report-cards.edit', [app('tenant')->name, $reportCard->id]) }}"
                       class="p-2 text-gray-400 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-12 text-center">
            <svg class="w-16 h-16 mx-auto text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <h3 class="text-lg font-medium text-white mb-2">Aucun bulletin</h3>
            <p class="text-gray-400">Générez des bulletins pour vos classes</p>
        </div>
        @endforelse
    </div>
    
    @if($reportCards->hasPages())
    <div class="mt-6">
        {{ $reportCards->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection