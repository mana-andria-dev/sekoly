<!-- resources/views/tenant/assignments/show.blade.php -->
@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">🔗</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Détails de l'Affectation</h1>
                        <p class="text-gray-400 text-sm mt-1">{{ $assignment->subject->name }} → {{ $assignment->schoolClass->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <div class="text-sm text-gray-500 hidden sm:flex items-center gap-2">
                    <a href="/dashboard" class="hover:text-gray-300 transition-colors">Dashboard</a>
                    <span class="text-gray-600">/</span>
                    <a href="/assignments" class="hover:text-gray-300 transition-colors">Affectations</a>
                    <span class="text-gray-600">/</span>
                    <span class="text-gray-300">{{ Str::limit($assignment->subject->name, 15) }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('assignments.edit', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}"
                       class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier
                    </a>
                    <a href="{{ route('assignments.index', [
                                    'tenant' => app('tenant')->name
                                ]) }}"
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

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Assignment Details Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                            <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                            Informations générales
                        </h2>
                        <span class="text-xs px-2 py-1 bg-gray-850 rounded-full text-gray-300">
                            ID: {{ $assignment->id }}
                        </span>
                    </div>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Class Info -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 inline-flex items-center gap-2">
                                    <span class="w-2 h-2 bg-primary-600 rounded-full"></span>
                                    Classe
                                </label>
                                <div class="mt-2 p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-primary-600/10 rounded-lg flex items-center justify-center">
                                            <span class="text-primary-600 text-xl">🏫</span>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white">{{ $assignment->schoolClass->name }}</h3>
                                            <div class="mt-1 space-y-1">
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Année scolaire:</span>
                                                    <span class="text-white">{{ $assignment->schoolClass->year->name ?? 'N/A' }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Créée le:</span>
                                                    <span class="text-white">{{ $assignment->schoolClass->created_at->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Subject Info -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 inline-flex items-center gap-2">
                                    <span class="w-2 h-2 bg-success rounded-full"></span>
                                    Matière
                                </label>
                                <div class="mt-2 p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-success/10 rounded-lg flex items-center justify-center">
                                            <span class="text-success text-xl">📚</span>
                                        </div>
                                        <div>
                                            <h3 class="text-lg font-bold text-white">{{ $assignment->subject->name }}</h3>
                                            <div class="mt-1 space-y-1">
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Code:</span>
                                                    <span class="font-mono text-white">{{ $assignment->subject->code }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Niveau:</span>
                                                    <span class="text-white">{{ $assignment->subject->level_label }}</span>
                                                </div>
                                                @if($assignment->subject->description)
                                                <div class="mt-2 text-sm text-gray-300">
                                                    {{ $assignment->subject->description }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Teacher Info -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 inline-flex items-center gap-2">
                                    <span class="w-2 h-2 bg-warning rounded-full"></span>
                                    Professeur
                                </label>
                                @if($assignment->teacher)
                                <div class="mt-2 p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-warning/10 rounded-lg flex items-center justify-center">
                                            <span class="text-warning text-xl">👨‍🏫</span>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-bold text-white">
                                                {{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}
                                            </h3>
                                            <div class="mt-1 space-y-1">
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Email:</span>
                                                    <span class="text-white">{{ $assignment->teacher->email }}</span>
                                                </div>
                                                <div class="flex items-center gap-2 text-sm">
                                                    <span class="text-gray-500">Téléphone:</span>
                                                    <span class="text-white">{{ $assignment->teacher->phone ?? 'Non renseigné' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="mt-2 p-6 bg-gray-850/30 rounded-lg border border-dashed border-gray-700 text-center">
                                    <div class="text-gray-500 mb-2">
                                        <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                        </svg>
                                    </div>
                                    <p class="text-gray-400">Aucun professeur assigné</p>
                                    <p class="text-sm text-gray-500 mt-1">Vous pouvez assigner un professeur en modifiant cette affectation</p>
                                </div>
                                @endif
                            </div>
                            
                            <!-- Stats -->
                            <div>
                                <label class="text-xs font-medium text-gray-500 uppercase tracking-wider mb-3 inline-flex items-center gap-2">
                                    <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                    Paramètres académiques
                                </label>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700 text-center">
                                        <div class="text-2xl font-bold text-white">{{ $assignment->hours_per_week }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Heures/semaine</div>
                                    </div>
                                    <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700 text-center">
                                        <div class="text-2xl font-bold text-purple-400">{{ $assignment->coefficient }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Coefficient</div>
                                    </div>
                                    <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700 text-center">
                                        <div class="text-2xl font-bold text-blue-400">{{ $assignment->hours_per_week * 36 }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Heures/an</div>
                                    </div>
                                    <div class="p-4 bg-gray-850/50 rounded-lg border border-gray-700 text-center">
                                        <div class="text-2xl font-bold text-green-400">{{ $assignment->hours_per_week * $assignment->coefficient }}</div>
                                        <div class="text-xs text-gray-500 mt-1">Poids total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Status & Dates -->
                    <div class="mt-8 pt-6 border-t border-gray-800">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="p-3 bg-gray-850/30 rounded-lg">
                                <div class="text-xs text-gray-500 mb-1">Statut</div>
                                <div class="flex items-center gap-2">
                                    <span class="relative flex h-3 w-3">
                                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $assignment->is_active ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
                                        <span class="relative inline-flex rounded-full h-3 w-3 {{ $assignment->is_active ? 'bg-green-500' : 'bg-red-500' }}"></span>
                                    </span>
                                    <span class="text-sm font-medium {{ $assignment->is_active ? 'text-green-400' : 'text-red-400' }}">
                                        {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                            <div class="p-3 bg-gray-850/30 rounded-lg">
                                <div class="text-xs text-gray-500 mb-1">Créée le</div>
                                <div class="text-sm text-white">{{ $assignment->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                            <div class="p-3 bg-gray-850/30 rounded-lg">
                                <div class="text-xs text-gray-500 mb-1">Modifiée le</div>
                                <div class="text-sm text-white">{{ $assignment->updated_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Related Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Class Stats -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                    <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                        <div class="w-2 h-5 bg-blue-500 rounded-full"></div>
                        Statistiques de la classe
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-gray-850/50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Matières assignées</div>
                            <div class="text-lg font-bold text-white">{{ $assignment->schoolClass->assignments()->count() }}</div>
                        </div>
                        <div class="p-3 bg-gray-850/50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Heures totales/sem</div>
                            <div class="text-lg font-bold text-white">
                                {{ $assignment->schoolClass->assignments()->sum('hours_per_week') }}
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Subject Stats -->
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                    <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                        <div class="w-2 h-5 bg-green-500 rounded-full"></div>
                        Statistiques de la matière
                    </h3>
                    <div class="space-y-3">
                        <div class="p-3 bg-gray-850/50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Classes assignées</div>
                            <div class="text-lg font-bold text-white">{{ $assignment->subject->classAssignments()->count() }}</div>
                        </div>
                        <div class="p-3 bg-gray-850/50 rounded-lg">
                            <div class="text-xs text-gray-500 mb-1">Professeurs assignés</div>
                            <div class="text-lg font-bold text-white">
                                {{ $assignment->subject->classAssignments()->whereNotNull('teacher_id')->distinct('teacher_id')->count() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Actions Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-warning rounded-full"></div>
                    Actions rapides
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('assignments.edit', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-primary-600/10 hover:bg-primary-600/20 text-primary-400 border border-primary-600/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Modifier l'affectation
                    </a>
                    
                    <form action="{{ route('assignments.toggle-active', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}" method="POST">
                        @csrf
                        <button type="submit" 
                                onclick="return confirm('Êtes-vous sûr de vouloir changer le statut de cette affectation ?')"
                                class="w-full flex items-center justify-center gap-2 p-3 {{ $assignment->is_active ? 'bg-danger/10 hover:bg-danger/20 text-danger' : 'bg-success/10 hover:bg-success/20 text-success' }} border border-current/20 rounded-lg transition-all duration-200">
                            @if($assignment->is_active)
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Désactiver l'affectation
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Activer l'affectation
                            @endif
                        </button>
                    </form>
                    
                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 border border-blue-600/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Voir la classe
                    </a>
                    
                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-green-600/10 hover:bg-green-600/20 text-green-400 border border-green-600/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        Voir la matière
                    </a>
                    
                    @if($assignment->teacher)
                    <a href="#"
                       class="w-full flex items-center justify-center gap-2 p-3 bg-warning/10 hover:bg-warning/20 text-warning border border-warning/20 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Voir le professeur
                    </a>
                    @endif
                </div>
            </div>
            
            <!-- Metadata (if exists) -->
            @if($assignment->metadata)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-purple-500 rounded-full"></div>
                    Métadonnées
                </h3>
                <div class="space-y-2">
                    @foreach($assignment->metadata as $key => $value)
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ $key }}:</span>
                        <span class="text-white">{{ is_array($value) ? json_encode($value) : $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
            
            <!-- Quick Stats -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-info rounded-full"></div>
                    Calculs
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Heures par trimestre</div>
                        <div class="text-lg font-bold text-white">{{ $assignment->hours_per_week * 12 }}</div>
                        <div class="text-xs text-gray-500 mt-1">12 semaines par trimestre</div>
                    </div>
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-xs text-gray-500 mb-1">Poids trimestriel</div>
                        <div class="text-lg font-bold text-purple-400">{{ $assignment->hours_per_week * 12 * $assignment->coefficient }}</div>
                        <div class="text-xs text-gray-500 mt-1">heures × coefficient</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation pour les stats
    const statsElements = document.querySelectorAll('.text-lg.font-bold');
    statsElements.forEach((element, index) => {
        setTimeout(() => {
            element.classList.add('animate-pulse');
            setTimeout(() => {
                element.classList.remove('animate-pulse');
            }, 1000);
        }, index * 200);
    });
});
</script>
@endpush