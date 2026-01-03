@extends('tenant.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="p-2 bg-primary-600/10 rounded-lg">
                <span class="text-xl text-primary-600">📅</span>
            </div>
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Modifier l'emploi du temps</h1>
                <p class="text-gray-400 text-sm mt-1">{{ $timetable->name }}</p>
            </div>
        </div>
    </div>

    <!-- Formulaire -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('timetables.update', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="space-y-6">
                <!-- Informations de base -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nom -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-400 mb-2">
                            Nom de l'emploi du temps *
                        </label>
                        <input type="text" id="name" name="name" required
                               value="{{ old('name', $timetable->name) }}"
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
                            <option value="{{ $class->id }}" {{ old('class_id', $timetable->class_id) == $class->id ? 'selected' : '' }}>
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
                              placeholder="Description optionnelle de l'emploi du temps...">{{ old('description', $timetable->description) }}</textarea>
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
                            <option value="{{ $year->id }}" {{ old('academic_year_id', $timetable->academic_year_id) == $year->id ? 'selected' : '' }}>
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
                            <option value="weekly" {{ old('type', $timetable->type) == 'weekly' ? 'selected' : '' }}>
                                Hebdomadaire
                            </option>
                            <option value="daily" {{ old('type', $timetable->type) == 'daily' ? 'selected' : '' }}>
                                Quotidien
                            </option>
                            <option value="custom" {{ old('type', $timetable->type) == 'custom' ? 'selected' : '' }}>
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
                               value="{{ old('start_date', $timetable->start_date->format('Y-m-d')) }}"
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
                               value="{{ old('end_date', $timetable->end_date->format('Y-m-d')) }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600 transition-colors">
                        @error('end_date')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Statut -->
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" id="is_active" name="is_active" value="1"
                               {{ old('is_active', $timetable->is_active) ? 'checked' : '' }}
                               class="w-4 h-4 text-primary-600 bg-gray-700 border-gray-600 rounded focus:ring-primary-500 focus:ring-2">
                        <label for="is_active" class="text-sm text-gray-300">
                            Emploi du temps actif
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 ml-7">
                        Si désactivé, l'emploi du temps ne sera plus visible dans les listes et ne pourra pas être utilisé.
                    </p>
                </div>
                
                <!-- Actions -->
                <div class="flex items-center justify-between gap-3 pt-4 border-t border-gray-800">
                    <div>
                        <a href="{{ route('timetables.show', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}"
                           class="px-4 py-2.5 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                            Annuler
                        </a>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <form action="{{ route('timetables.destroy', ['tenant' => app('tenant')->name, 'timetable' => $timetable->id]) }}" 
                              method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    onclick="return confirm('Archiver cet emploi du temps ?')"
                                    class="px-4 py-2.5 bg-red-600/10 hover:bg-red-600/20 text-red-400 rounded-lg text-sm font-medium transition-all duration-200">
                                Archiver
                            </button>
                        </form>
                        
                        <button type="submit"
                                class="px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                            Enregistrer les modifications
                        </button>
                    </div>
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
    }
});

// Initialiser la date min pour la date de fin
document.addEventListener('DOMContentLoaded', function() {
    const startDate = document.getElementById('start_date').value;
    if (startDate) {
        document.getElementById('end_date').min = startDate;
    }
});
</script>
@endsection