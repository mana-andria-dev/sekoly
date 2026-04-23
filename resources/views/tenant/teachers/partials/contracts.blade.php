<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Contrats du professeur</h2>
            <a href="{{ route('teachers.contracts.create', ['teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-3 py-1.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouveau contrat
            </a>
        </div>
    </div>
    
    <div class="p-6">
        @if($teacher->contracts->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Numéro
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Type
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Période
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Salaire/Taux
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($teacher->contracts as $contract)
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <td class="px-4 py-4">
                            <div class="font-medium text-white">{{ $contract->contract_number }}</div>
                            <div class="text-xs text-gray-500">{{ $contract->contract_type }}</div>
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $typeColors = [
                                    'CDI' => 'bg-purple-600/10 text-purple-400 border-purple-600/20',
                                    'CDD' => 'bg-yellow-600/10 text-yellow-400 border-yellow-600/20',
                                    'Vacataire' => 'bg-pink-600/10 text-pink-400 border-pink-600/20',
                                    'Contractuel' => 'bg-indigo-600/10 text-indigo-400 border-indigo-600/20',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $typeColors[$contract->contract_type] ?? 'bg-gray-600/10 text-gray-400' }} border">
                                {{ $contract->contract_type }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="text-sm text-white">
                                {{ $contract->start_date->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-500">
                                @if($contract->end_date)
                                au {{ $contract->end_date->format('d/m/Y') }}
                                @else
                                Indéterminé
                                @endif
                            </div>
                        </td>
                        <td class="px-4 py-4">
                            @if($contract->salary)
                            <div class="text-sm text-white">{{ number_format($contract->salary, 2, ',', ' ') }} Ar</div>
                            <div class="text-xs text-gray-500">Salaire mensuel</div>
                            @elseif($contract->hourly_rate)
                            <div class="text-sm text-white">{{ number_format($contract->hourly_rate, 2, ',', ' ') }} Ar/h</div>
                            <div class="text-xs text-gray-500">{{ $contract->hours_per_week }}h/semaine</div>
                            @else
                            <div class="text-sm text-gray-400">Non spécifié</div>
                            @endif
                        </td>
                        <td class="px-4 py-4">
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-600/10 text-green-400',
                                    'draft' => 'bg-gray-600/10 text-gray-400',
                                    'expired' => 'bg-red-600/10 text-red-400',
                                    'terminated' => 'bg-orange-600/10 text-orange-400',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ $statusColors[$contract->status] ?? 'bg-gray-600/10 text-gray-400' }}">
                                {{ ucfirst($contract->status) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <div class="flex items-center gap-2">
                                @if($contract->document_path)
                                <a href="{{ Storage::url($contract->document_path) }}" 
                                   target="_blank"
                                   class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Voir le document">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-12">
            <div class="text-gray-500 mb-4">
                <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-300 mb-2">Aucun contrat</h3>
            <p class="text-sm text-gray-500 mb-6">Aucun contrat n'a été enregistré pour ce professeur</p>
            <a href="{{ route('teachers.contracts.create', ['teacher' => $teacher->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Ajouter un contrat
            </a>
        </div>
        @endif
    </div>
</div>