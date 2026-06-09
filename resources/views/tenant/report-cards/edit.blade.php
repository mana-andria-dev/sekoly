{{-- resources/views/tenant/report-cards/edit.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Modifier le bulletin</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</p>
            </div>
            <a href="{{ route('report-cards.show', $reportCard->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('report-cards.update', $reportCard->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Appréciation générale</label>
                    <textarea name="appreciation" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">{{ old('appreciation', $reportCard->appreciation) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commentaires du professeur principal</label>
                    <textarea name="teacher_comments" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">{{ old('teacher_comments', $reportCard->teacher_comments) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Commentaires de la direction</label>
                    <textarea name="principal_comments" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600">{{ old('principal_comments', $reportCard->principal_comments) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Absences (une par ligne)</label>
                    <textarea name="absences" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600"
                              placeholder="Ex: 2 absences injustifiées&#10;1 retard le 15/03">{{ old('absences', is_array($reportCard->absences) ? implode("\n", $reportCard->absences) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Comportement (une par ligne)</label>
                    <textarea name="behaviors" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600"
                              placeholder="Ex: Participation active en classe&#10;Travail sérieux">{{ old('behaviors', is_array($reportCard->behaviors) ? implode("\n", $reportCard->behaviors) : '') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                    <select name="status" class="w-full md:w-1/3 bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        <option value="draft" {{ old('status', $reportCard->status) == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status', $reportCard->status) == 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="archived" {{ old('status', $reportCard->status) == 'archived' ? 'selected' : '' }}>Archivé</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('report-cards.show', $reportCard->id) }}"
                       class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                        Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection