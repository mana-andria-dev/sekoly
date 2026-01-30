@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-primary-600/10 rounded-lg">
                        <span class="text-xl text-primary-600">⏰</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Gestion des créneaux</h1>
                        <p class="text-gray-400 text-sm mt-1">{{ $timetable->name }} - {{ $timetable->class->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('timetables.show', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Retour
                </a>
                <!-- 
                <a href="{{ route('timetables.generate-from-assignments', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                   onclick="return confirm('Générer automatiquement les créneaux à partir des affectations ? Cela écrasera les créneaux existants.')"
                   class="px-4 py-2.5 bg-green-600 hover:bg-green-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    Générer automatiquement
                </a>
                 -->
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Formulaire d'ajout -->
        <div class="lg:col-span-1">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Ajouter un créneau</h3>
                
                <form action="{{ route('timetables.add-slot', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" 
                      method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <!-- Affectation (optionnel) -->
                        <!-- 
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Basé sur une affectation</label>
                            <select name="assignment_id"
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                <option value="">Sélectionner une affectation</option>
                                @foreach($assignments as $assignment)
                                <option value="{{ $assignment->id }}">
                                    {{ $assignment->subject->code }} - {{ $assignment->subject->name }}
                                    @if($assignment->teacher)
                                    ({{ $assignment->teacher->name }})
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        -->
                        
                        <!-- Jour de la semaine -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Jour *</label>
                            <select name="day_of_week" required
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                <option value="1">Lundi</option>
                                <option value="2">Mardi</option>
                                <option value="3">Mercredi</option>
                                <option value="4">Jeudi</option>
                                <option value="5">Vendredi</option>
                                <option value="6">Samedi</option>
                                <option value="7">Dimanche</option>
                            </select>
                        </div>
                        
                        <!-- Horaires -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Heure début *</label>
                                <input type="time" name="start_time" required
                                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-400 mb-2">Heure fin *</label>
                                <input type="time" name="end_time" required
                                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                            </div>
                        </div>
                        
                        <!-- Matière -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Matière *</label>
                            <select name="subject_id" required
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Professeur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Professeur</label>
                            <select name="teacher_id"
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                <option value="">Sélectionner un professeur</option>
                                @foreach($teachers as $teacher)
                                <option value="{{ $teacher->user_id ?? $teacher->id }}">
                                    {{ $teacher->full_name }}
                                    @if($teacher->specialization)
                                    - {{ $teacher->specialization }}
                                    @endif
                                </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <!-- Salle 
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Salle</label>
                            <select name="classroom_id"
                                    class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                <option value="">Sélectionner une salle</option>
                                @foreach($classrooms as $classroom)
                                <option value="{{ $classroom->id }}">{{ $classroom->name }} ({{ $classroom->capacity }} places)</option>
                                @endforeach
                            </select>
                        </div>
                        -->
                        
                        <!-- Couleur -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Couleur</label>
                            <div class="flex items-center gap-3">
                                <input type="color" name="color" value="#3B82F6"
                                       class="w-10 h-10 bg-gray-800 border border-gray-700 rounded-lg cursor-pointer p-1">
                                <div class="flex-1">
                                    <input type="text" name="color_text" value="#3B82F6"
                                           class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Notes</label>
                            <textarea name="notes" rows="2"
                                      class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white"
                                      placeholder="Notes optionnelles..."></textarea>
                        </div>
                        
                        <button type="submit"
                                class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 rounded-lg transition-all duration-200">
                            Ajouter le créneau
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Statistiques -->
            <div class="mt-6 bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Statistiques</h3>
                
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Créneaux planifiés</span>
                        <span class="text-white font-medium">{{ $timetable->timetableSlots->count() }}</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Heures/semaine</span>
                        @php
                            $totalHours = $timetable->timetableSlots->sum(function($slot) {
                                $start = strtotime($slot->start_time);
                                $end = strtotime($slot->end_time);
                                return ($end - $start) / 3600;
                            });
                        @endphp
                        <span class="text-white font-medium">{{ number_format($totalHours, 1) }}h</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Matières différentes</span>
                        <span class="text-white font-medium">
                            {{ $timetable->timetableSlots->pluck('subject_id')->unique()->count() }}
                        </span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <span class="text-gray-400">Affectations non planifiées</span>
                        <span class="text-white font-medium">{{ $assignments->count() - $timetable->timetableSlots->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Liste des créneaux par jour -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
                <div class="p-6 border-b border-gray-800">
                    <h3 class="text-lg font-semibold text-white">Créneaux planifiés</h3>
                </div>
                
                <div class="divide-y divide-gray-800">
                    @php
                        $days = [
                            1 => 'Lundi',
                            2 => 'Mardi', 
                            3 => 'Mercredi',
                            4 => 'Jeudi',
                            5 => 'Vendredi',
                            6 => 'Samedi',
                            7 => 'Dimanche'
                        ];
                    @endphp
                    
                    @foreach($days as $dayNumber => $dayName)
                    @php
                        $daySlots = $timetable->timetableSlots->where('day_of_week', $dayNumber)->sortBy('start_time');
                        $dayHours = $daySlots->sum(function($slot) {
                            $start = strtotime($slot->start_time);
                            $end = strtotime($slot->end_time);
                            return ($end - $start) / 3600;
                        });
                    @endphp
                    
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-md font-semibold text-white">
                                {{ $dayName }}
                            </h4>
                            <span class="text-sm text-gray-400">{{ number_format($dayHours, 1) }}h</span>
                        </div>
                        
                        <div class="space-y-3">
                            @forelse($daySlots as $slot)
                            <div class="flex items-center justify-between p-3 bg-gray-800/50 rounded-lg group hover:bg-gray-800 transition-colors"
                                 style="border-left: 4px solid {{ $slot->color }}">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-1">
                                        <span class="font-medium text-white">{{ $slot->subject->name }}</span>
                                        <span class="text-xs px-2 py-1 bg-gray-700 rounded">{{ $slot->subject->code }}</span>
                                        <span class="text-xs text-gray-400">{{ $slot->start_time->format('H:i') }} - {{ $slot->end_time->format('H:i') }}</span>
                                    </div>
                                    <div class="text-sm text-gray-400">
                                        @if($slot->teacherProfile)
                                        <span class="flex items-center gap-1">
                                            👨‍🏫 {{ $slot->teacherProfile->full_name }}
                                        </span>
                                        @elseif($slot->teacher)
                                        <span class="flex items-center gap-1">
                                            👨‍🏫 {{ $slot->teacher->name }}
                                        </span>
                                        @endif
                                        @if($slot->classroom)
                                        <span class="flex items-center gap-1 mt-1">
                                            🏫 {{ $slot->classroom->name }}
                                        </span>
                                        @endif
                                        @if($slot->notes)
                                        <p class="text-xs text-gray-500 mt-1">{{ $slot->notes }}</p>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <!-- Éditer -->
                                    <button type="button"
                                            onclick="editSlot({{ $slot->id }})"
                                            class="p-1.5 text-gray-400 hover:text-white hover:bg-gray-700 rounded-lg transition-colors"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    
                                    <!-- Supprimer -->
                                    <form action="{{ route('timetable-slots.destroy', ['tenant' => app('tenant')->name, 'timetable_slot' => $slot->id]) }}" 
                                          method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('Supprimer ce créneau ?')"
                                                class="p-1.5 text-gray-400 hover:text-red-400 hover:bg-gray-700 rounded-lg transition-colors"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-4">
                                <span class="text-gray-600 text-sm">Aucun créneau planifié</span>
                            </div>
                            @endforelse
                            
                            <!-- Ajouter un créneau rapide pour ce jour -->
                            <div class="mt-3 pt-3 border-t border-gray-800">
                                <button type="button"
                                        onclick="addSlotForDay({{ $dayNumber }}, '{{ $dayName }}')"
                                        class="flex items-center gap-2 text-sm text-gray-400 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Ajouter un créneau le {{ $dayName }}
                                </button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
<div class="mt-6 bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-semibold text-white">Vue hebdomadaire</h3>
    </div>
    
    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-400 uppercase tracking-wider min-w-20">
                            Heure
                        </th>
                        @foreach(['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi'] as $dayIndex => $dayName)
                        @php
                            $dayNumber = $dayIndex + 1;
                            $daySlots = $timetable->timetableSlots->where('day_of_week', $dayNumber);
                        @endphp
                        <th class="px-3 py-2 text-center text-xs font-medium text-gray-400 uppercase tracking-wider min-w-32">
                            <div>{{ $dayName }}</div>
                            <div class="text-xs text-gray-500">{{ $daySlots->count() }} créneaux</div>
                        </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    <!-- Générer les créneaux de 7h à 18h par tranches de 1h -->
                    @php
                        $startHour = 7; // 7h du matin
                        $endHour = 18; // 18h (6h du soir)
                    @endphp
                    
                    @for($hour = $startHour; $hour < $endHour; $hour++)
                    @php
                        $startTime = sprintf('%02d:00', $hour);
                        $endTime = sprintf('%02d:00', $hour + 1);
                        
                        // Pour l'affichage formaté
                        $displayStart = sprintf('%dh', $hour);
                        $displayEnd = sprintf('%dh', $hour + 1);
                    @endphp
                    <tr class="hover:bg-gray-850/30">
                        <td class="px-3 py-2 bg-gray-850/50">
                            <div class="text-center">
                                <div class="text-sm font-medium text-white">{{ $displayStart }} - {{ $displayEnd }}</div>
                            </div>
                        </td>
                        
                        @for($day = 1; $day <= 5; $day++)
                        @php
                            $daySlots = $timetable->timetableSlots->where('day_of_week', $day);
                            
                            // Trouver les créneaux qui se superposent avec cette tranche horaire
                            $matchingSlots = $daySlots->filter(function($slot) use ($startTime, $endTime) {
                                $slotStart = $slot->start_time->format('H:i');
                                $slotEnd = $slot->end_time->format('H:i');
                                
                                // Vérifier si le créneau se superpose avec la tranche horaire
                                return ($slotStart < $endTime && $slotEnd > $startTime);
                            });
                        @endphp
                        
                        <td class="px-3 py-2 border-l border-gray-800 min-h-12">
                            @if($matchingSlots->isNotEmpty())
                                <!-- Afficher tous les créneaux qui se superposent avec cette tranche -->
                                <div class="space-y-1">
                                    @foreach($matchingSlots as $slot)
                                    <div class="p-1.5 rounded text-xs cursor-pointer hover:opacity-90 transition-opacity"
                                         style="background-color: {{ $slot->color }}20; border-left: 2px solid {{ $slot->color }}"
                                         title="{{ $slot->subject->name }} - {{ optional($slot->teacherProfile)->full_name ?? 'Professeur non défini' }}">

                                        
                                        <div class="font-medium truncate text-white">{{ $slot->subject->code }}</div>
                                        <div class="text-gray-300 truncate">{{ $slot->subject->name }}</div>
                                        @if($slot->teacherProfile)
                                        <div class="text-gray-400 text-xs truncate">{{ $slot->teacherProfile->first_name }}</div>
                                        @elseif($slot->teacher)
                                        <div class="text-gray-400 text-xs truncate">{{ $slot->teacher->name }}</div>
                                        @endif
                                        
                                    </div>
                                    @endforeach
                                </div>
                            @else
                            <div class="text-center py-2">
                                <span class="text-gray-600 text-xs">—</span>
                            </div>
                            @endif
                        </td>
                        @endfor
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<script>
// Fonction pour pré-remplir le formulaire avec un jour spécifique
function addSlotForDay(dayNumber, dayName) {
    document.querySelector('select[name="day_of_week"]').value = dayNumber;
    document.querySelector('select[name="day_of_week"]').dispatchEvent(new Event('change'));
    
    // Scroller vers le formulaire
    document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
    
    // Focus sur le premier champ
    document.querySelector('input[name="start_time"]').focus();
}

// Fonction pour éditer un créneau (simplifiée pour l'instant)
function editSlot(slotId) {
    alert('Fonction d\'édition à implémenter. Slot ID: ' + slotId);
    // À implémenter: ouvrir un modal ou formulaire d'édition
}

// Synchroniser le champ couleur texte avec le color picker
document.querySelector('input[name="color"]').addEventListener('input', function(e) {
    document.querySelector('input[name="color_text"]').value = e.target.value;
});

document.querySelector('input[name="color_text"]').addEventListener('input', function(e) {
    document.querySelector('input[name="color"]').value = e.target.value;
});

// Quand une affectation est sélectionnée, remplir automatiquement les champs
document.querySelector('select[name="assignment_id"]').addEventListener('change', function(e) {
    if (e.target.value) {
        // Ici, vous pourriez faire une requête AJAX pour récupérer les infos de l'affectation
        // Pour l'instant, on laisse l'utilisateur remplir manuellement
        alert('Sélectionnez manuellement la matière et le professeur basés sur cette affectation.');
    }
});
</script>
@endsection