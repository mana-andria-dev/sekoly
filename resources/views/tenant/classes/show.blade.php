<!-- resources/views/tenant/classes/show.blade.php -->
@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">🏫</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $schoolClass->name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">Détails de la classe</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="/classes" class="hover:text-gray-300 transition-colors">Classes</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">{{ Str::limit($schoolClass->name, 15) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    
                    <a href="{{ route('classes.edit', [
                                    'class' => $schoolClass->id
                                ]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('classes.index') }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-800">
            <nav class="flex space-x-8">
                <button type="button" data-tab="details"
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-primary-600 text-primary-600">
                    Détails de la classe
                </button>
                <button type="button" data-tab="students" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Élèves <span class="ml-1 px-2 py-0.5 text-xs bg-gray-800 rounded-full">{{ $schoolClass->students->count() }}</span>
                </button>
                <button type="button" data-tab="assignments" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Affectations <span class="ml-1 px-2 py-0.5 text-xs bg-primary-600/20 text-primary-400 rounded-full">{{ $assignmentStats['total'] }}</span>
                </button>
                <button type="button" data-tab="timetable" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Emploi du temps
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="space-y-6">
        <!-- Details Tab (Default visible) -->
        <div id="details-content" class="tab-content">
            <!-- Details Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                        Informations générales
                    </h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Nom de la classe</label>
                                <p class="mt-1 text-lg font-medium text-white">{{ $schoolClass->name }}</p>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Année scolaire</label>
                                @if($schoolClass->year)
                                <div class="mt-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-600/10 text-blue-400 border border-blue-600/20">
                                        {{ $schoolClass->year->name }}
                                    </span>
                                    @if($schoolClass->year->is_active ?? false)
                                    <span class="ml-2 text-sm text-green-400">(Active)</span>
                                    @endif
                                </div>
                                @else
                                <p class="mt-1 text-gray-500">Non spécifiée</p>
                                @endif
                            </div>
                        </div>
                        <div class="space-y-6">
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Statistiques</label>
                                <div class="mt-2 grid grid-cols-2 gap-4">
                                    <div class="p-3 bg-gray-850/50 rounded-lg">
                                        <div class="text-2xl font-bold text-white">{{ $schoolClass->students->count() }}</div>
                                        <div class="text-xs text-gray-500">Élèves</div>
                                    </div>
                                    <div class="p-3 bg-gray-850/50 rounded-lg">
                                        <div class="text-2xl font-bold text-purple-400">{{ $assignmentStats['total'] }}</div>
                                        <div class="text-xs text-gray-500">Matières</div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Dates</label>
                                <div class="mt-2 space-y-1">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Créée le:</span>
                                        <span class="text-white">{{ $schoolClass->created_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Modifiée le:</span>
                                        <span class="text-white">{{ $schoolClass->updated_at->format('d/m/Y à H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="#"
                   class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover group transition-all duration-200 hover:border-primary-600">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-primary-600/10 rounded-lg group-hover:bg-primary-600/20 transition-colors">
                            <span class="text-primary-600 group-hover:text-primary-500">🔗</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-300 group-hover:text-white">Assigner des matières</h3>
                            <p class="text-xs text-gray-500 mt-1">Ajouter plusieurs matières à cette classe</p>
                        </div>
                    </div>
                </a>
                
                <a href="#"
                   class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover group transition-all duration-200 hover:border-success">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-success/10 rounded-lg group-hover:bg-success/20 transition-colors">
                            <span class="text-success group-hover:text-success/90">📚</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-300 group-hover:text-white">Nouvelle affectation</h3>
                            <p class="text-xs text-gray-500 mt-1">Assigner une matière spécifique</p>
                        </div>
                    </div>
                </a>
                
                <a href="#"
                   class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover group transition-all duration-200 hover:border-warning">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-warning/10 rounded-lg group-hover:bg-warning/20 transition-colors">
                            <span class="text-warning group-hover:text-warning/90">👥</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold text-gray-300 group-hover:text-white">Gérer les élèves</h3>
                            <p class="text-xs text-gray-500 mt-1">Ajouter/retirer des élèves</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Assignments Preview -->
            @if($schoolClass->assignments->count() > 0)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-sm font-semibold text-gray-300">Matières assignées</h3>
                    <button type="button" data-tab="assignments" class="text-xs text-primary-400 hover:text-primary-300 tab-link">
                        Voir toutes →
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($schoolClass->assignments->take(6) as $assignment)
                    <div class="p-3 bg-gray-850/50 rounded-lg border border-gray-700">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <div class="font-medium text-white">{{ $assignment->subject->name }}</div>
                                <div class="text-xs text-gray-500">{{ $assignment->subject->code }}</div>
                            </div>
                            <span class="text-xs px-2 py-1 bg-blue-600/10 text-blue-400 rounded">
                                {{ $assignment->hours_per_week }}h
                            </span>
                        </div>
                        @if($assignment->teacher)
                        <div class="flex items-center gap-2 mt-2">
                            <div class="w-6 h-6 bg-warning/10 rounded-full flex items-center justify-center">
                                <span class="text-warning text-xs">👨‍🏫</span>
                            </div>
                            <div class="text-xs text-gray-300">
                                {{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        <!-- Students Tab (Hidden by default) -->
        <div id="students-content" class="tab-content" style="display: none;">
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                            <div class="w-2 h-6 bg-green-500 rounded-full"></div>
                            Élèves de la classe
                        </h2>
                        <span class="text-xs px-2 py-1 bg-green-600/10 text-green-400 rounded-full">
                            {{ $schoolClass->students->count() }} élève(s)
                        </span>
                    </div>
                </div>
                <div class="p-6">
                    @if($schoolClass->students->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-850">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Nom & Prénom
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Téléphone
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                        Inscrit le
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @foreach($schoolClass->students as $student)
                                <tr class="hover:bg-gray-850/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 bg-green-600/10 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 text-sm">👤</span>
                                            </div>
                                            <div class="font-medium text-white">
                                                {{ $student->first_name }} {{ $student->last_name }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $student->email }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">{{ $student->phone ?? 'Non renseigné' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-300">
                                        {{ $student->pivot->created_at->format('d/m/Y') ?? '-' }}
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
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300 mb-2">Aucun élève dans cette classe</h3>
                        <p class="text-sm text-gray-500">Les élèves seront assignés via le module d'inscription</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Assignments Tab (Hidden by default) -->
        <div id="assignments-content" class="tab-content" style="display: none;">
            @include('tenant.classes.partials.assignments', ['class' => $schoolClass])
        </div>

        <!-- Timetable Tab (Hidden by default) -->
        <div id="timetable-content" class="tab-content" style="display: none;">
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-orange-500 rounded-full"></div>
                        Emploi du temps
                    </h2>
                </div>
                <div class="p-6">
                    <div class="text-center py-12">
                        <div class="text-gray-500 mb-4">
                            <svg class="w-16 h-16 mx-auto text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-300 mb-2">Module emploi du temps</h3>
                        <p class="text-sm text-gray-500 mb-6">Ce module sera disponible prochainement</p>
                        <div class="inline-flex items-center gap-2 px-4 py-2 bg-gray-850 border border-gray-700 rounded-lg text-gray-400 text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Bientôt disponible
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


<script>

    console.log('Script de tabs chargé 111'); // Pour debug
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script de tabs chargé 222'); // Pour debug
    
    // Get all tab buttons and content areas
    const tabButtons = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    console.log('Nombre de boutons trouvés:', tabButtons.length); // Debug
    
    // Function to switch tabs
    function switchTab(tabName) {
        console.log('Changement vers l\'onglet:', tabName); // Debug
        
        // Hide all tab contents
        tabContents.forEach(content => {
            content.style.display = 'none';
            content.classList.remove('active');
        });
        
        // Remove active class from all tab buttons
        tabButtons.forEach(button => {
            button.classList.remove('border-primary-600', 'text-primary-600');
            button.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-300', 'hover:border-gray-700');
        });
        
        // Show the selected tab content
        const activeContent = document.getElementById(tabName + '-content');
        if (activeContent) {
            activeContent.style.display = 'block';
            activeContent.classList.add('active');
        }
        
        // Add active class to clicked tab button
        const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-300', 'hover:border-gray-700');
            activeButton.classList.add('border-primary-600', 'text-primary-600');
        }
    }
    
    // Add click event to all tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const tabName = this.dataset.tab;
            console.log('Clic sur onglet:', tabName); // Debug
            switchTab(tabName);
            
            // Update URL hash
            if(history.pushState) {
                history.pushState(null, null, '#' + tabName);
            } else {
                location.hash = '#' + tabName;
            }
        });
    });
    
    // Check URL hash on page load
    const hash = window.location.hash.substring(1);
    const validTabs = ['details', 'students', 'assignments', 'timetable'];
    
    if (hash && validTabs.includes(hash)) {
        console.log('Hash détecté:', hash); // Debug
        switchTab(hash);
    } else {
        // Default to details tab
        console.log('Par défaut: details'); // Debug
        switchTab('details');
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function() {
        const hash = window.location.hash.substring(1);
        if (hash && validTabs.includes(hash)) {
            switchTab(hash);
        }
    });
});
</script>
