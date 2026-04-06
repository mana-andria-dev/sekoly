{{-- resources/views/tenant/report-cards/class.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Bulletins - {{ $class->name }}</h1>
                <p class="text-gray-400 text-sm mt-1">
                    @if($period == 'trimester1') 1er Trimestre
                    @elseif($period == 'trimester2') 2ème Trimestre
                    @elseif($period == 'trimester3') 3ème Trimestre
                    @elseif($period == 'annual') Annuel
                    @else Toutes périodes
                    @endif
                </p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('report-cards.index', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase">Moyenne Générale</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase">Mention</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase">Rang</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-400 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($reportCards as $reportCard)
                    <tr class="hover:bg-gray-850">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-white">{{ $reportCard->student->first_name }} {{ $reportCard->student->last_name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="text-sm font-semibold {{ $reportCard->overall_average >= 10 ? 'text-green-400' : 'text-red-400' }}">
                                {{ number_format($reportCard->overall_average ?? 0, 2) }}/20
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($reportCard->mention == 'Très Bien') bg-purple-900/50 text-purple-400
                                @elseif($reportCard->mention == 'Bien') bg-blue-900/50 text-blue-400
                                @elseif($reportCard->mention == 'Assez Bien') bg-green-900/50 text-green-400
                                @elseif($reportCard->mention == 'Passable') bg-yellow-900/50 text-yellow-400
                                @else bg-red-900/50 text-red-400
                                @endif">
                                {{ $reportCard->mention }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-300">
                            {{ $reportCard->class_rank }}/{{ $reportCard->total_students }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($reportCard->status == 'published') bg-green-900/50 text-green-400
                                @elseif($reportCard->status == 'draft') bg-yellow-900/50 text-yellow-400
                                @else bg-gray-900/50 text-gray-400
                                @endif">
                                {{ ucfirst($reportCard->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('report-cards.show', [app('tenant')->name, $reportCard->id]) }}"
                                   class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('report-cards.print', [app('tenant')->name, $reportCard->id]) }}" target="_blank"
                                   class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('report-cards.edit', [app('tenant')->name, $reportCard->id]) }}"
                                   class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            Aucun bulletin trouvé
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection