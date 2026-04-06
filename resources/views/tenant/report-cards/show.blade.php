{{-- resources/views/tenant/report-cards/show.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Bulletin scolaire</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }} - {{ $reportCard->class->name }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('report-cards.index', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('report-cards.print', [app('tenant')->name, $reportCard->id]) }}" target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Imprimer
                </a>
                <a href="{{ route('report-cards.edit', [app('tenant')->name, $reportCard->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                @if($reportCard->status == 'draft')
                <form action="{{ route('report-cards.publish', [app('tenant')->name, $reportCard->id]) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Publier
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- En-tête du bulletin -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-white">BULLETIN SCOLAIRE</h2>
            <p class="text-gray-400">{{ $reportCard->schoolYear->name ?? 'Année scolaire' }}</p>
            <p class="text-gray-400">
                @if($reportCard->period == 'trimester1') 1er TRIMESTRE
                @elseif($reportCard->period == 'trimester2') 2ème TRIMESTRE
                @elseif($reportCard->period == 'trimester3') 3ème TRIMESTRE
                @else ANNÉE SCOLAIRE
                @endif
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-4 bg-gray-800 rounded-lg">
            <div>
                <p class="text-gray-400 text-sm">Élève</p>
                <p class="text-white font-semibold">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Classe</p>
                <p class="text-white font-semibold">{{ $reportCard->class->name }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Date d'émission</p>
                <p class="text-white font-semibold">{{ $reportCard->issued_date->format('d/m/Y') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-sm">Statut</p>
                <p class="inline-flex px-2 py-1 text-xs rounded-full 
                    @if($reportCard->status == 'published') bg-green-900/50 text-green-400
                    @elseif($reportCard->status == 'draft') bg-yellow-900/50 text-yellow-400
                    @else bg-gray-900/50 text-gray-400
                    @endif">
                    {{ ucfirst($reportCard->status) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Tableau des notes par matière -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
        <h3 class="text-lg font-semibold text-white mb-4">Résultats par matière</h3>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Matière</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Moyenne /20</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Moyenne Classe</th>
                        <th class="px-4 py-3 text-center text-xs font-medium text-gray-400 uppercase">Coef.</th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase">Appréciation</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($reportCard->subject_grades as $subject)
                    <tr class="hover:bg-gray-850">
                        <td class="px-4 py-3 text-sm text-white">{{ $subject['subject_name'] }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($subject['average'])
                                <span class="text-sm font-semibold {{ $subject['average'] >= 10 ? 'text-green-400' : 'text-red-400' }}">
                                    {{ number_format($subject['average'], 2) }}
                                </span>
                            @else
                                <span class="text-sm text-gray-500">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-400">
                            {{ $subject['class_average'] ? number_format($subject['class_average'], 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-400">{{ $subject['coefficient'] }}</td>
                        <td class="px-4 py-3 text-sm text-gray-300">{{ $subject['appreciation'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-850">
                    <tr>
                        <td class="px-4 py-3 text-sm font-semibold text-white">MOYENNE GÉNÉRALE</td>
                        <td class="px-4 py-3 text-center">
                            <span class="text-xl font-bold {{ $reportCard->overall_average >= 10 ? 'text-green-400' : 'text-red-400' }}">
                                {{ number_format($reportCard->overall_average ?? 0, 2) }}/20
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-400">
                            {{ $reportCard->class_average ? number_format($reportCard->class_average, 2) : '-' }}
                        </td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Classement et statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-primary-400">{{ $reportCard->class_rank ?? '-' }}</div>
            <div class="text-sm text-gray-400 mt-1">Rang dans la classe</div>
            <div class="text-xs text-gray-500">sur {{ $reportCard->total_students ?? '-' }} élèves</div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-purple-400">{{ $reportCard->mention }}</div>
            <div class="text-sm text-gray-400 mt-1">Mention</div>
        </div>
        
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center">
            <div class="text-3xl font-bold text-blue-400">
                @php
                    $difference = $reportCard->overall_average ? $reportCard->overall_average - ($reportCard->class_average ?? 0) : 0;
                @endphp
                {{ $difference >= 0 ? '+' : '' }}{{ number_format($difference, 2) }}
            </div>
            <div class="text-sm text-gray-400 mt-1">vs Moyenne Classe</div>
        </div>
    </div>

    <!-- Appréciations et commentaires -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @if($reportCard->appreciation)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-md font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                </svg>
                Appréciation générale
            </h3>
            <p class="text-gray-300">{{ $reportCard->appreciation }}</p>
        </div>
        @endif

        @if($reportCard->teacher_comments)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-md font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                Commentaires du professeur principal
            </h3>
            <p class="text-gray-300">{{ $reportCard->teacher_comments }}</p>
        </div>
        @endif

        @if($reportCard->principal_comments)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-md font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Commentaires de la direction
            </h3>
            <p class="text-gray-300">{{ $reportCard->principal_comments }}</p>
        </div>
        @endif
    </div>

    <!-- Absences et comportement -->
    @if(($reportCard->absences && count($reportCard->absences) > 0) || ($reportCard->behaviors && count($reportCard->behaviors) > 0))
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        @if($reportCard->absences && count($reportCard->absences) > 0)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-md font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Absences
            </h3>
            <ul class="space-y-2">
                @foreach($reportCard->absences as $absence)
                <li class="text-gray-300 text-sm">{{ $absence }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @if($reportCard->behaviors && count($reportCard->behaviors) > 0)
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
            <h3 class="text-md font-semibold text-white mb-3 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m4-10V5a2 2 0 112 0v5m-6 0h10m-6 0v10"/>
                </svg>
                Comportement
            </h3>
            <ul class="space-y-2">
                @foreach($reportCard->behaviors as $behavior)
                <li class="text-gray-300 text-sm">{{ $behavior }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif
</div>
@endsection