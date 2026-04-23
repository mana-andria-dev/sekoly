<div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
        <div class="flex items-center justify-between">
            <h2 class="text-lg font-semibold text-white">Emploi du temps</h2>
            <div class="flex items-center gap-3">
                <button type="button" class="text-sm text-gray-400 hover:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                </button>
                <button type="button" class="text-sm text-gray-400 hover:text-gray-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <div class="p-6">
        <!-- Disponibilités -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-300">Disponibilités hebdomadaires</h3>
                <button type="button" 
                        class="text-xs text-primary-400 hover:text-primary-300"
                        onclick="document.getElementById('add-availability-form').classList.toggle('hidden')">
                    + Ajouter une disponibilité
                </button>
            </div>
            
            <!-- Formulaire d'ajout de disponibilité (caché par défaut) -->
            <div id="add-availability-form" class="hidden mb-4 p-4 bg-gray-800/50 rounded-lg">
                <form action="{{ route('teachers.availabilities.store', ['teacher' => $teacher->id]) }}" 
                      method="POST" class="space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Jour</label>
                            <select name="day_of_week" class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                                <option value="monday">Lundi</option>
                                <option value="tuesday">Mardi</option>
                                <option value="wednesday">Mercredi</option>
                                <option value="thursday">Jeudi</option>
                                <option value="friday">Vendredi</option>
                                <option value="saturday">Samedi</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Heure début</label>
                            <input type="time" name="start_time" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-400 mb-1">Heure fin</label>
                            <input type="time" name="end_time" 
                                   class="w-full bg-gray-700 border border-gray-600 rounded px-3 py-2 text-sm text-white">
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="flex items-center gap-2 text-sm text-gray-400">
                            <input type="checkbox" name="is_recurring" value="1" checked class="rounded">
                            Récurrent
                        </label>
                        <div class="flex-1"></div>
                        <button type="button" 
                                onclick="document.getElementById('add-availability-form').classList.add('hidden')"
                                class="px-3 py-1.5 text-sm bg-gray-700 hover:bg-gray-600 rounded text-gray-300">
                            Annuler
                        </button>
                        <button type="submit" 
                                class="px-3 py-1.5 text-sm bg-primary-600 hover:bg-primary-700 rounded text-white">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Liste des disponibilités -->
            @if($teacher->availabilities->count() > 0)
            <div class="space-y-2">
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                    @php
                        $dayAvailabilities = $teacher->availabilities->where('day_of_week', $day);
                        $dayNames = [
                            'monday' => 'Lundi',
                            'tuesday' => 'Mardi',
                            'wednesday' => 'Mercredi',
                            'thursday' => 'Jeudi',
                            'friday' => 'Vendredi',
                            'saturday' => 'Samedi',
                        ];
                    @endphp
                    @if($dayAvailabilities->count() > 0)
                    <div class="p-3 bg-gray-800/30 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div class="text-sm font-medium text-gray-300">{{ $dayNames[$day] }}</div>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($dayAvailabilities as $availability)
                            <div class="flex items-center gap-2 px-3 py-1.5 bg-gray-700 rounded text-sm">
                                <span class="text-gray-300">
                                    {{ \Carbon\Carbon::parse($availability->start_time)->format('H:i') }} - 
                                    {{ \Carbon\Carbon::parse($availability->end_time)->format('H:i') }}
                                </span>
                                <form action="{{ route('teachers.availabilities.destroy', [
                                            'teacher' => $teacher->id,
                                            'availability' => $availability->id
                                        ]) }}" 
                                      method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            onclick="return confirm('Supprimer cette disponibilité ?')"
                                            class="text-gray-500 hover:text-red-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
            @else
            <div class="text-center py-4">
                <p class="text-sm text-gray-500">Aucune disponibilité enregistrée</p>
            </div>
            @endif
        </div>
        
        <!-- Emploi du temps hebdomadaire -->
        <div>
            <h3 class="text-sm font-semibold text-gray-300 mb-4">Planning hebdomadaire</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Heure
                            </th>
                            @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi'] as $day)
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                {{ $day }}
                            </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-800">
                        @for($hour = 8; $hour <= 18; $hour++)
                        <tr>
                            <td class="px-4 py-3 text-sm text-gray-400 bg-gray-850/50">
                                {{ sprintf('%02d:00', $hour) }}
                            </td>
                            @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'] as $day)
                            <td class="px-4 py-3 border border-gray-800">
                                @php
                                    $hasClass = false;
                                    // Ici tu devras remplir avec les véritables cours du professeur
                                @endphp
                                @if($hasClass)
                                <div class="p-2 bg-primary-600/10 border border-primary-600/20 rounded text-xs">
                                    <div class="font-medium text-white">Mathématiques</div>
                                    <div class="text-gray-400">6ème A</div>
                                </div>
                                @endif
                            </td>
                            @endforeach
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            
            <div class="mt-4 text-sm text-gray-500">
                <p>Note : L'emploi du temps détaillé sera disponible une fois le module emploi du temps implémenté.</p>
            </div>
        </div>
    </div>
</div>