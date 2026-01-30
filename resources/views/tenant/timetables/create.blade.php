@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-primary-600/10 rounded-lg">
                <span class="text-xl text-primary-600">📅</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouvel emploi du temps</h1>
                <p class="text-gray-400 text-sm mt-1">Créer un nouvel emploi du temps pour une classe</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('timetables.store', ['tenant' => app('tenant')->name]) }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2">
                            Nom de l'emploi du temps *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors"
                               placeholder="Ex: Emploi du temps 2024-2025 - Terminale A">
                        @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Classe -->
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-400 mb-2">
                            Classe *
                        </label>
                        <select id="class_id" name="class_id" required
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                            <option value="">Sélectionner une classe</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if($class->year)
                                - {{ $class->year->name }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('class_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-400 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors"
                              placeholder="Description optionnelle de l'emploi du temps...">{{ old('description') }}</textarea>
                    @error('description')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Période -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Année académique -->
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-400 mb-2">
                            Année académique *
                        </label>
                        <select id="academic_year_id" name="academic_year_id" required
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                            <option value="">Sélectionner une année académique</option>
                            @foreach($academicYears as $year)
                            <option value="{{ $year->id }}" {{ old('academic_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                                @if($year->is_active)
                                <span class="text-green-400">(Active)</span>
                                @endif
                            </option>
                            @endforeach
                        </select>
                        @error('academic_year_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Type -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-400 mb-2">
                            Type d'emploi du temps
                        </label>
                        <select id="type" name="type"
                                class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                            <option value="weekly" {{ old('type', 'weekly') == 'weekly' ? 'selected' : '' }}>
                                Hebdomadaire
                            </option>
                            <option value="daily" {{ old('type') == 'daily' ? 'selected' : '' }}>
                                Quotidien
                            </option>
                            <option value="custom" {{ old('type') == 'custom' ? 'selected' : '' }}>
                                Personnalisé
                            </option>
                        </select>
                    </div>
                </div>
                
                <!-- Dates -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Date de début -->
                    <div>
                        <label for="start_date" class="block text-sm font-medium text-gray-400 mb-2">
                            Date de début *
                        </label>
                        <input type="date" id="start_date" name="start_date" required
                               value="{{ old('start_date', date('Y-m-d')) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                        @error('start_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Date de fin -->
                    <div>
                        <label for="end_date" class="block text-sm font-medium text-gray-400 mb-2">
                            Date de fin *
                        </label>
                        <input type="date" id="end_date" name="end_date" required
                               value="{{ old('end_date', date('Y-m-d', strtotime('+6 months'))) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                        @error('end_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Options -->
                <!-- 
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="generate_from_assignments" name="generate_from_assignments" value="1"
                               class="w-4 h-4 text-primary-600 bg-gray-700 border-gray-600 rounded focus:ring-primary-500 focus:ring-2">
                        <label for="generate_from_assignments" class="text-sm text-gray-300">
                            Générer automatiquement à partir des affectations existantes
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 ml-7">
                        Si coché, l'emploi du temps sera automatiquement rempli avec les cours basés sur les affectations de la classe.
                        Vous pourrez toujours modifier les créneaux ensuite.
                    </p>
                </div>
                -->
                
                <!-- Actions -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-800">
                    <a href="{{ route('timetables.index', ['tenant' => app('tenant')->name]) }}"
                       class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        Créer l'emploi du temps
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Script pour définir la date de fin minimum
document.getElementById('start_date').addEventListener('change', function() {
    const startDate = this.value;
    const endDateInput = document.getElementById('end_date');
    
    if (startDate) {
        endDateInput.min = startDate;
        
        // Si la date de fin est avant la date de début, la mettre à jour
        if (endDateInput.value && endDateInput.value < startDate) {
            const start = new Date(startDate);
            start.setMonth(start.getMonth() + 6); // +6 mois par défaut
            endDateInput.value = start.toISOString().split('T')[0];
        }
    }
});
</script>
@endsection