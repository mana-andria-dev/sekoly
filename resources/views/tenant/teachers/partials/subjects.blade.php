<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Matières enseignées</h2>
            <span class="text-sm text-gray-400">
                {{ $teacher->assignments->count() }} affectation(s)
            </span>
        </div>
    </div>
    
    <div class="p-6">
        @if($teacher->assignments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Matière
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Classe
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Horaires
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Période
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Statut
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @foreach($teacher->assignments as $assignment)
                        <tr class="hover:bg-gray-850/50 transition-colors">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-primary-600/10 rounded flex items-center justify-center">
                                        <span class="text-primary-400 text-xs font-semibold">
                                            {{ substr($assignment->subject->code, 0, 2) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="font-medium text-white">{{ $assignment->subject->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $assignment->subject->code }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-white">{{ $assignment->schoolClass->name }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->schoolClass->level ?? '' }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-white">{{ $assignment->hours_per_week }}h/semaine</div>
                                <div class="text-xs text-gray-500">
                                    @if($assignment->day_of_week)
                                        {{ ucfirst($assignment->day_of_week) }}
                                    @else
                                        Non spécifié
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm text-white">
                                    {{ $assignment->start_date->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($assignment->end_date)
                                        au {{ $assignment->end_date->format('d/m/Y') }}
                                    @else
                                        Indéterminée
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-600/10 text-green-400',
                                        'ended' => 'bg-gray-600/10 text-gray-400',
                                        'pending' => 'bg-yellow-600/10 text-yellow-400',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$assignment->status] ?? 'bg-gray-600/10 text-gray-400' }}">
                                    {{ ucfirst($assignment->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-850/50">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-sm text-gray-400">
                                Total heures/semaine
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-lg font-bold text-primary-400">
                                    {{ $teacher->assignments->sum('hours_per_week') }}h
                                </div>
                            </td>
                            <td colspan="2" class="px-4 py-3 text-right">
                                <a href="#assignments-content" 
                                   onclick="switchTab('assignments')"
                                   class="text-sm text-primary-400 hover:text-primary-300">
                                    Voir toutes les affectations →
                                </a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <div class="text-gray-500 mb-4">
                    <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-300 mb-2">Aucune matière assignée</h3>
                <p class="text-sm text-gray-500">Ce professeur n'est actuellement assigné à aucune matière</p>
            </div>
        @endif
    </div>
</div>