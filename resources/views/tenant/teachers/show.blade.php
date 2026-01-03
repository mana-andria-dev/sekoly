@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="relative">
                        @if($teacher->photo)
                        <img src="{{ Storage::url($teacher->photo) }}" alt="{{ $teacher->full_name }}" 
                             class="w-16 h-16 rounded-full object-cover border-2 border-primary-600">
                        @else
                        <div class="w-16 h-16 bg-primary-600/10 rounded-full flex items-center justify-center border-2 border-primary-600">
                            <span class="text-2xl text-primary-600">👨‍🏫</span>
                        </div>
                        @endif
                        @if($teacher->status === 'active')
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-gray-900"></div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">{{ $teacher->full_name }}</h1>
                        <p class="text-gray-400 text-sm mt-1">{{ $teacher->teacher_id }} • {{ $teacher->specialization }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('teachers.edit', ['tenant' => app('tenant')->name, 'teacher' => $teacher->id]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('teachers.index', ['tenant' => app('tenant')->name]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="mb-6">
        <div class="border-b border-gray-800">
            <nav class="flex space-x-8">
                <button type="button" data-tab="overview"
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-primary-600 text-primary-600">
                    Vue d'ensemble
                </button>
                <button type="button" data-tab="subjects" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Matières <span class="ml-1 px-2 py-0.5 text-xs bg-gray-800 rounded-full">{{ $teacher->assignments->count() }}</span>
                </button>
                <button type="button" data-tab="contracts" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Contrats
                </button>
                <button type="button" data-tab="evaluations" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Évaluations
                </button>
                <button type="button" data-tab="schedule" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Emploi du temps
                </button>
                <button type="button" data-tab="assignments" 
                        class="tab-link py-3 px-1 border-b-2 font-medium text-sm transition-colors duration-200 border-transparent text-gray-500 hover:text-gray-300 hover:border-gray-700">
                    Affectations <span class="ml-1 px-2 py-0.5 text-xs bg-primary-600/20 text-primary-400 rounded-full">{{ $teacher->assignments->count() }}</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="space-y-6">
        <!-- Vue d'ensemble -->
        <div id="overview-content" class="tab-content">
            <!-- Statistiques -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div class="text-2xl font-bold text-white">{{ $weeklyWorkload }}h</div>
                    <div class="text-sm text-gray-400">Charge hebdomadaire</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div class="text-2xl font-bold text-green-400">{{ $teacher->classes->count() }}</div>
                    <div class="text-sm text-gray-400">Classes</div>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                    <div class="text-2xl font-bold text-blue-400">{{ $teacher->years_of_service }} ans</div>
                    <div class="text-sm text-gray-400">Ancienneté</div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Informations personnelles -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Informations personnelles</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Email</span>
                            <span class="text-white">{{ $teacher->email }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Téléphone</span>
                            <span class="text-white">{{ $teacher->phone ?? 'Non renseigné' }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Date de naissance</span>
                            <span class="text-white">{{ $teacher->date_of_birth->format('d/m/Y') }} ({{ $teacher->age }} ans)</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Nationalité</span>
                            <span class="text-white">{{ $teacher->nationality ?? 'Non renseignée' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Informations professionnelles -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-white mb-4">Informations professionnelles</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Statut</span>
                            @php
                                $statusColors = [
                                    'active' => 'bg-green-600/10 text-green-400 border-green-600/20',
                                    'on_leave' => 'bg-blue-600/10 text-blue-400 border-blue-600/20',
                                    'inactive' => 'bg-gray-600/10 text-gray-400 border-gray-600/20',
                                ];
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $statusColors[$teacher->status] }} border">
                                {{ ucfirst($teacher->status) }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Type d'emploi</span>
                            <span class="text-white">{{ $teacher->employment_type }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Date d'embauche</span>
                            <span class="text-white">{{ $teacher->hire_date->format('d/m/Y') }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-gray-800">
                            <span class="text-gray-400">Diplôme</span>
                            <span class="text-white">{{ $teacher->academic_degree }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Matières principales -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-white mb-4">Matières principales</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($teacher->subjects->where('pivot.is_primary', true)->take(6) as $subject)
                    <div class="p-4 bg-gray-800/50 rounded-lg border border-gray-700">
                        <div class="font-medium text-white">{{ $subject->name }}</div>
                        <div class="text-xs text-gray-500 mt-1">{{ $subject->code }}</div>
                        <div class="flex items-center justify-between mt-3">
                            <span class="text-xs text-gray-400">
                                {{ $subject->pivot->experience_years }} ans d'exp.
                            </span>
                            <span class="text-xs px-2 py-1 bg-primary-600/10 text-primary-400 rounded">
                                {{ ucfirst($subject->pivot->proficiency_level) }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Matières -->
        <div id="subjects-content" class="tab-content" style="display: none;">
            @include('tenant.teachers.partials.subjects')
        </div>

        <!-- Contrats -->
        <div id="contracts-content" class="tab-content" style="display: none;">
            @include('tenant.teachers.partials.contracts')
        </div>

        <!-- Évaluations -->
        <div id="evaluations-content" class="tab-content" style="display: none;">
            @include('tenant.teachers.partials.evaluations')
        </div>

        <!-- Emploi du temps -->
        <div id="schedule-content" class="tab-content" style="display: none;">
            @include('tenant.teachers.partials.schedule')
        </div>

        <!-- Affectations -->
        <div id="assignments-content" class="tab-content" style="display: none;">
            @include('tenant.teachers.partials.assignments')
        </div>
    </div>
</div>
@endsection

<script>
// Script pour les onglets (identique à celui des classes)
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-link');
    const tabContents = document.querySelectorAll('.tab-content');
    
    function switchTab(tabName) {
        // Hide all tab contents
        tabContents.forEach(content => {
            content.style.display = 'none';
        });
        
        // Remove active class from all tab buttons
        tabButtons.forEach(button => {
            button.classList.remove('border-primary-600', 'text-primary-600');
            button.classList.add('border-transparent', 'text-gray-500');
        });
        
        // Show the selected tab content
        const activeContent = document.getElementById(tabName + '-content');
        if (activeContent) {
            activeContent.style.display = 'block';
        }
        
        // Add active class to clicked tab button
        const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
        if (activeButton) {
            activeButton.classList.remove('border-transparent', 'text-gray-500');
            activeButton.classList.add('border-primary-600', 'text-primary-600');
        }
    }
    
    // Add click event to all tab buttons
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            switchTab(tabName);
        });
    });
    
    // Default to overview tab
    switchTab('overview');
});
</script>