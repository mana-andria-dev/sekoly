<div class="bg-gray-900 border border-gray-800 rounded-xl p-6 mb-6">
    <h3 class="text-lg font-semibold text-white mb-4">Ajouter une affectation</h3>
    
    <form id="addAssignmentForm" method="POST" 
          action="{{ route('teachers.assignments.store', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- Classe -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Classe</label>
                <select name="class_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <option value="">Sélectionner une classe</option>
                    @foreach($availableClasses as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <!-- Matière -->
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Matière</label>
                <select name="subject_id" required
                        class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                    <option value="">Sélectionner une matière</option>
                    @foreach($availableSubjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->name }} ({{ $subject->code }})</option>
                    @endforeach
                </select>
            </div>
        </div>
        
        <!--
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Heures/semaine</label>
                <input type="number" name="hours_per_week" required min="1" max="40"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Coefficient</label>
                <input type="number" step="0.1" name="coefficient" required min="0.1" max="10"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Jour</label>
                <select name="day_of_week"
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
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Date de début</label>
                <input type="date" name="start_date" required
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Date de fin (optionnel)</label>
                <input type="date" name="end_date"
                       class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
            </div>
        </div>
        -->
        
        <div class="flex justify-end gap-3">
            <button type="button" onclick="resetForm()"
                    class="px-4 py-2.5 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white hover:bg-gray-800 transition-all duration-200">
                Annuler
            </button>
            <button type="submit"
                    class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                Ajouter l'affectation
            </button>
        </div>
    </form>
</div>

<script>
function resetForm() {
    document.getElementById('addAssignmentForm').reset();
}
</script>