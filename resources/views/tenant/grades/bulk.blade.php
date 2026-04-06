{{-- resources/views/tenant/grades/bulk.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Saisie groupée des notes</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $class->name }} - {{ $subject->name }}</p>
            </div>
            <a href="{{ route('grades.index', app('tenant')->name) }}"
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
        <form action="{{ route('grades.bulk.store', app('tenant')->name) }}" method="POST">
            @csrf
            <input type="hidden" name="class_id" value="{{ $class->id }}">
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type de note *</label>
                    <select name="grade_type" required 
                            class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        <option value="homework">Devoir</option>
                        <option value="test">Test</option>
                        <option value="quiz">Quiz</option>
                        <option value="participation">Participation</option>
                        <option value="project">Projet</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date *</label>
                    <input type="date" name="grade_date" required value="{{ date('Y-m-d') }}"
                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
                    <select name="period" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white">
                        <option value="">Non spécifiée</option>
                        <option value="trimester1">1er Trimestre</option>
                        <option value="trimester2">2ème Trimestre</option>
                        <option value="trimester3">3ème Trimestre</option>
                        <option value="annual">Annuel</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-300 mb-2">Titre (optionnel)</label>
                <input type="text" name="title" 
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white"
                       placeholder="Ex: Devoir du 15 mars">
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-800">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Note / Max</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Coefficient</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Commentaire</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($students as $index => $student)
                        <tr class="hover:bg-gray-850">
                            <td class="px-4 py-3">
                                <div class="text-sm text-white">{{ $student->first_name }} {{ $student->last_name }}</div>
                                <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <input type="number" 
                                           name="grades[{{ $index }}][score]" 
                                           step="0.01"
                                           min="0"
                                           class="w-24 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white"
                                           placeholder="Note">
                                    <span class="text-gray-500">/</span>
                                    <input type="number" 
                                           name="grades[{{ $index }}][max_score]" 
                                           value="20"
                                           step="0.5"
                                           min="1"
                                           class="w-20 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <input type="number" 
                                       name="grades[{{ $index }}][coefficient]" 
                                       value="1"
                                       step="0.5"
                                       min="0.5"
                                       max="5"
                                       class="w-20 bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            </td>
                            <td class="px-4 py-3">
                                <input type="text" 
                                       name="grades[{{ $index }}][comment]" 
                                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white"
                                       placeholder="Commentaire...">
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('grades.index', app('tenant')->name) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Enregistrer toutes les notes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection