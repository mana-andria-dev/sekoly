@extends('tenant.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-gray-850 rounded-lg">
                <span class="text-xl">📄</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Générer un document</h1>
                <p class="text-gray-400 text-sm mt-1">Pour : {{ $student->name }}</p>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form method="POST" action="{{ route('students.documents.store', $student->id) }}">
            @csrf
            
            <div class="space-y-6">
                <!-- Type de document -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type de document *</label>
                    <select name="document_type" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-primary-500" required>
                        <option value="">Sélectionnez un type</option>
                        @foreach($documentTypes as $key => $label)
                            <option value="{{ $key }}" {{ old('document_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Titre -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Titre du document *</label>
                    <input type="text" 
                           name="title" 
                           value="{{ old('title') }}"
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-primary-500"
                           placeholder="Ex: Certificat de scolarité 2025"
                           required>
                </div>
                
                <!-- Description -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description (optionnelle)</label>
                    <textarea name="description" 
                              rows="3"
                              class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-primary-500"
                              placeholder="Informations complémentaires...">{{ old('description') }}</textarea>
                </div>
                
                <!-- Date d'expiration -->
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date d'expiration (optionnelle)</label>
                    <input type="date" 
                           name="expires_at" 
                           value="{{ old('expires_at') }}"
                           class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:border-primary-500">
                    <p class="text-xs text-gray-500 mt-1">Laissez vide pour une validité permanente</p>
                </div>
                
                <!-- Aperçu des informations -->
                <div class="bg-gray-800 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-300 mb-3">📋 Informations de l'élève</h3>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">Nom complet:</span>
                            <span class="text-white ml-2">{{ $student->name }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Date naissance:</span>
                            <span class="text-white ml-2">{{ $student->date_of_birth?->format('d/m/Y') ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Classe actuelle:</span>
                            <span class="text-white ml-2">{{ $currentEnrollment?->schoolClass?->name ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Matricule:</span>
                            <span class="text-white ml-2">{{ $currentEnrollment?->roll_number ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-500">Année scolaire:</span>
                            <span class="text-white ml-2">{{ $schoolYear?->name ?? date('Y') }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Boutons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-800">
                    <a href="{{ route('students.show', $student->id) }}" 
                       class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Générer et télécharger
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection