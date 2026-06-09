{{-- resources/views/tenant/report-cards/create.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Générer des bulletins</h1>
                <p class="text-gray-400 text-sm mt-1">Génération des bulletins pour une classe et une période</p>
            </div>
            <a href="{{ route('report-cards.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('report-cards.generate') }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Classe *</label>
                        <select name="class_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Année scolaire *</label>
                        <select name="school_year_id" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une année</option>
                            @foreach($schoolYears as $year)
                                <option value="{{ $year->id }}">{{ $year->name }} ({{ $year->start_date->format('Y') }} - {{ $year->end_date->format('Y') }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Période *</label>
                        <select name="period" required class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                            <option value="">Sélectionner une période</option>
                            <option value="trimester1">1er Trimestre</option>
                            <option value="trimester2">2ème Trimestre</option>
                            <option value="trimester3">3ème Trimestre</option>
                            <option value="annual">Annuel</option>
                        </select>
                    </div>
                </div>
                
                <div class="bg-yellow-900/20 border border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-400">Attention</h3>
                            <p class="text-xs text-yellow-300 mt-1">
                                La génération des bulletins va calculer automatiquement les moyennes pour chaque élève 
                                en fonction des notes saisies pour la période sélectionnée. Les bulletins existants pour 
                                cette période seront mis à jour.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('report-cards.index') }}"
                       class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                        Générer les bulletins
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection