@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouveau contrat</h1>
                <p class="text-gray-400 text-sm mt-1">Pour {{ $teacher->full_name }}</p>
            </div>
            <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('teachers.contracts.store', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <!-- Informations du contrat -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Informations du contrat
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Type de contrat *</label>
                            <select name="contract_type" required class="w-full bg-gray-800 border {{ $errors->has('contract_type') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="CDI" {{ old('contract_type') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ old('contract_type') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Vacataire" {{ old('contract_type') == 'Vacataire' ? 'selected' : '' }}>Vacataire</option>
                                <option value="Contractuel" {{ old('contract_type') == 'Contractuel' ? 'selected' : '' }}>Contractuel</option>
                            </select>
                            @error('contract_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date de début *</label>
                            <input type="date" name="start_date" required value="{{ old('start_date') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('start_date') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('start_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date de fin</label>
                            <input type="date" name="end_date" value="{{ old('end_date') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('end_date') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('end_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">Laisser vide pour contrat à durée indéterminée</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Rémunération -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Rémunération
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Salaire mensuel (Ar)</label>
                            <input type="number" step="0.01" name="salary" value="{{ old('salary') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('salary') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="0.00">
                            @error('salary')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taux horaire (Ar/h)</label>
                            <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate', $teacher->hourly_rate) }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('hourly_rate') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="0.00">
                            @error('hourly_rate')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Heures/semaine</label>
                            <input type="number" name="hours_per_week" value="{{ old('hours_per_week', $teacher->hours_per_week) }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('hours_per_week') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('hours_per_week')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Document -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Document
                </h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Document du contrat</label>
                        <input type="file" name="document" accept=".pdf,.doc,.docx"
                               class="w-full bg-gray-800 border {{ $errors->has('document') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                        @error('document')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-xs text-gray-500">PDF, DOC, DOCX - Max 5MB</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                        <textarea name="notes" rows="3"
                                  class="w-full bg-gray-800 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">{{ old('notes') }}</textarea>
                        @error('notes')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('teachers.show', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Créer le contrat
                </button>
            </div>
        </div>
    </form>
</div>
@endsection