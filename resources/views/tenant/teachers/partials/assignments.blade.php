<div class="space-y-6">
    <!-- Formulaire d'ajout -->
    @include('tenant.teachers.partials.assignment-form')
    
    <!-- Liste des affectations -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-white">Affectations actuelles</h2>
                <div class="flex items-center gap-3">
                    <span class="text-sm text-gray-400">{{ $teacher->assignments->count() }} affectation(s)</span>
                </div>
            </div>
        </div>
        
        <div class="p-6">
            @if($teacher->assignments->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full min-w-full">
                    <thead class="bg-gray-850">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Classe
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Matière
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
                        @foreach($teacher->assignments as $assignment)
                        <tr class="hover:bg-gray-850/50 transition-colors" id="assignment-{{ $assignment->id }}">
                            <td class="px-4 py-4">
                                <div class="font-medium text-white">{{ $assignment->schoolClass->name }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->schoolClass->level }}</div>
                            </td>
                            <td class="px-4 py-4">
                                <div class="font-medium text-white">{{ $assignment->subject->name }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->subject->code }}</div>
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
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <button onclick="editAssignment({{ $assignment->id }})"
                                            class="p-2 text-gray-400 hover:text-primary-400 hover:bg-primary-600/10 rounded-lg transition-colors"
                                            title="Modifier">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <form method="POST" 
                                          action="{{ route('teachers.assignments.destroy', ['teacher' => $teacher->id, 'assignment' => $assignment->id]) }}"
                                          onsubmit="return confirm('Voulez-vous vraiment supprimer cette affectation ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="p-2 text-gray-400 hover:text-red-400 hover:bg-red-600/10 rounded-lg transition-colors"
                                                title="Supprimer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-300 mb-2">Aucune affectation</h3>
                <p class="text-sm text-gray-500">Ce professeur n'est actuellement affecté à aucune classe</p>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'édition -->
<div id="editModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-900 border border-gray-800 rounded-xl w-full max-w-lg mx-4">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-white">Modifier l'affectation</h3>
            <button onclick="closeEditModal()"
                    class="text-gray-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <div class="p-6">
            <form id="editAssignmentForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="assignment_id" id="edit_assignment_id">
                
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Heures/semaine</label>
                            <input type="number" name="hours_per_week" id="edit_hours_per_week" required min="1" max="40"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Coefficient</label>
                            <input type="number" step="0.1" name="coefficient" id="edit_coefficient" required min="0.1" max="10"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Jour de la semaine</label>
                        <select name="day_of_week" id="edit_day_of_week"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            <option value="">Tous les jours</option>
                            <option value="monday">Lundi</option>
                            <option value="tuesday">Mardi</option>
                            <option value="wednesday">Mercredi</option>
                            <option value="thursday">Jeudi</option>
                            <option value="friday">Vendredi</option>
                            <option value="saturday">Samedi</option>
                            <option value="sunday">Dimanche</option>
                        </select>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Date de début</label>
                            <input type="date" name="start_date" id="edit_start_date" required
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-2">Date de fin</label>
                            <input type="date" name="end_date" id="edit_end_date"
                                   class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Statut</label>
                        <select name="status" id="edit_status"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            <option value="active">Actif</option>
                            <option value="ended">Terminé</option>
                            <option value="pending">En attente</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" onclick="closeEditModal()"
                            class="px-4 py-2.5 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200">
                        Annuler
                    </button>
                    <button type="submit"
                            class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        Enregistrer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editAssignment(assignmentId) {
    // Récupérer les données de l'affectation
    fetch(`/{{ tenant()->name }}/api/assignments/${assignmentId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const assignment = data.assignment;
                
                // Remplir le formulaire
                document.getElementById('edit_assignment_id').value = assignment.id;
                document.getElementById('edit_hours_per_week').value = assignment.hours_per_week;
                document.getElementById('edit_coefficient').value = assignment.coefficient;
                document.getElementById('edit_day_of_week').value = assignment.day_of_week || '';
                document.getElementById('edit_start_date').value = assignment.start_date;
                document.getElementById('edit_end_date').value = assignment.end_date || '';
                document.getElementById('edit_status').value = assignment.status;
                
                // Afficher le modal
                document.getElementById('editModal').classList.remove('hidden');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors du chargement des données');
        });
}

function closeEditModal() {
    document.getElementById('editModal').classList.add('hidden');
}

// Gérer la soumission du formulaire d'édition
document.getElementById('editAssignmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const assignmentId = document.getElementById('edit_assignment_id').value;
    const formData = new FormData(this);
    
    fetch(`/{{ tenant()->name }}/api/assignments/${assignmentId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'X-HTTP-Method-Override': 'PUT'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            location.reload();
        } else {
            alert(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Erreur lors de la mise à jour');
    });
});

// Empêcher la fermeture du modal en cliquant dessus
document.getElementById('editModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>