@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header Section -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">📊</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Tableau de bord</h1>
                        <p class="text-gray-400 text-sm mt-1">Aperçu général de votre établissement</p>
                    </div>
                </div>
            </div>
            
            <!-- Date & Time -->
            <div class="flex items-center gap-3">
                <div class="px-4 py-2 bg-gray-850 border border-gray-700 rounded-lg">
                    <span class="text-sm text-gray-300">{{ now()->format('d M Y') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        <!-- Utilisateurs Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-primary-600/10 rounded-lg">
                    <span class="text-primary-600 text-xl">👥</span>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 bg-primary-600/10 text-primary-600 rounded-full">+12%</span>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-400 mb-2">Utilisateurs</h3>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-white">{{ $usersCount }}</h2>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total actifs</p>
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-green-500 font-medium">+5 ce mois</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-800">
                <div class="flex items-center gap-2">
                    <div class="flex-1 bg-gray-800 rounded-full h-2">
                        <div class="bg-primary-600 h-2 rounded-full" style="width: 85%"></div>
                    </div>
                    <span class="text-xs text-gray-400">85% actifs</span>
                </div>
            </div>
        </div>

        <!-- Année active Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-success/10 rounded-lg">
                    <span class="text-success text-xl">📅</span>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 bg-success/10 text-success rounded-full">{{ now()->format('M') }}</span>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-400 mb-2">Année active</h3>
            <div class="flex items-end justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-white mb-1">{{ $activeYear?->name ?? 'Aucune' }}</h2>
                    <p class="text-sm text-gray-500">Année scolaire en cours</p>
                </div>
                @if($activeYear)
                <div class="text-right">
                    <div class="w-12 h-12 bg-success/10 rounded-full flex items-center justify-center">
                        <span class="text-success text-lg">✓</span>
                    </div>
                </div>
                @endif
            </div>
            @if($activeYear)
            <div class="mt-4 pt-4 border-t border-gray-800">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Progression</span>
                    <span class="text-success font-medium">En cours</span>
                </div>
            </div>
            @endif
        </div>

        <!-- Classes Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-info/10 rounded-lg">
                    <span class="text-info text-xl">📚</span>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 bg-info/10 text-info rounded-full">24</span>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-400 mb-2">Classes</h3>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-white">24</h2>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total actives</p>
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-green-500 font-medium">+3 ce mois</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-800">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Taux de remplissage</span>
                    <span class="text-info font-medium">92%</span>
                </div>
                <div class="mt-2 flex items-center gap-2">
                    <div class="flex-1 bg-gray-800 rounded-full h-2">
                        <div class="bg-info h-2 rounded-full" style="width: 92%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Élèves Card -->
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-warning/10 rounded-lg">
                    <span class="text-warning text-xl">🎓</span>
                </div>
                <div class="text-right">
                    <span class="text-xs px-2 py-1 bg-warning/10 text-warning rounded-full">642</span>
                </div>
            </div>
            <h3 class="text-sm font-medium text-gray-400 mb-2">Élèves</h3>
            <div class="flex items-end justify-between">
                <h2 class="text-3xl font-bold text-white">642</h2>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total inscrits</p>
                    <div class="flex items-center gap-1 mt-1">
                        <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-xs text-green-500 font-medium">+42 ce mois</span>
                    </div>
                </div>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-800">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-400">Moyenne par classe</span>
                    <span class="text-warning font-medium">26.8</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Activités récentes -->
        <div class="lg:col-span-2">
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-success rounded-full"></div>
                        Activités récentes
                    </h3>
                    <button class="text-sm text-primary-600 hover:text-primary-500 font-medium transition-colors">
                        Voir tout →
                    </button>
                </div>
                
                <div class="space-y-4">
                    <!-- Activité 1 -->
                    <div class="flex items-center gap-4 p-4 bg-gray-850/50 rounded-lg">
                        <div class="w-10 h-10 bg-primary-600/10 rounded-full flex items-center justify-center">
                            <span class="text-primary-600">👤</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-white">Nouvel utilisateur</h4>
                            <p class="text-xs text-gray-400 mt-1">Jean Dupont s'est inscrit comme enseignant</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Il y a 2h</span>
                        </div>
                    </div>
                    
                    <!-- Activité 2 -->
                    <div class="flex items-center gap-4 p-4 bg-gray-850/50 rounded-lg">
                        <div class="w-10 h-10 bg-success/10 rounded-full flex items-center justify-center">
                            <span class="text-success">📚</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-white">Classe créée</h4>
                            <p class="text-xs text-gray-400 mt-1">La classe "Terminale S" a été ajoutée</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Il y a 5h</span>
                        </div>
                    </div>
                    
                    <!-- Activité 3 -->
                    <div class="flex items-center gap-4 p-4 bg-gray-850/50 rounded-lg">
                        <div class="w-10 h-10 bg-info/10 rounded-full flex items-center justify-center">
                            <span class="text-info">📊</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-white">Rapport généré</h4>
                            <p class="text-xs text-gray-400 mt-1">Rapport trimestriel téléchargé par l'administration</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Hier, 14:30</span>
                        </div>
                    </div>
                    
                    <!-- Activité 4 -->
                    <div class="flex items-center gap-4 p-4 bg-gray-850/50 rounded-lg">
                        <div class="w-10 h-10 bg-warning/10 rounded-full flex items-center justify-center">
                            <span class="text-warning">🔔</span>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-sm font-medium text-white">Notification système</h4>
                            <p class="text-xs text-gray-400 mt-1">Sauvegarde automatique effectuée avec succès</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-gray-500">Hier, 23:00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Actions rapides -->
        <div class="space-y-6">
            <!-- Quick Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Actions rapides
                </h3>
                
                <div class="space-y-3">
                    <a href="/classes"
                       class="flex items-center justify-between p-3 bg-gray-850 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-primary-600/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-primary-600">📚</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Gérer les classes</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="/students"
                       class="flex items-center justify-between p-3 bg-gray-850 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-success/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-success">🎓</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir les élèves</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="/school-years/create"
                       class="flex items-center justify-between p-3 bg-gray-850 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-warning/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-warning">📅</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Années scolaires</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-warning" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    <a href="/users"
                       class="flex items-center justify-between p-3 bg-gray-850 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-info/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-info">👥</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Gérer les utilisateurs</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            <!-- Statut système -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6 card-hover">
                <h3 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-success rounded-full"></div>
                    Statut système
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-success rounded-full"></div>
                            <span class="text-sm text-gray-300">Serveur</span>
                        </div>
                        <span class="text-xs px-2 py-1 bg-success/10 text-success rounded-full">En ligne</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-success rounded-full"></div>
                            <span class="text-sm text-gray-300">Base de données</span>
                        </div>
                        <span class="text-xs px-2 py-1 bg-success/10 text-success rounded-full">Connectée</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-warning rounded-full"></div>
                            <span class="text-sm text-gray-300">Stockage</span>
                        </div>
                        <span class="text-xs px-2 py-1 bg-warning/10 text-warning rounded-full">78% utilisé</span>
                    </div>
                    
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-success rounded-full"></div>
                            <span class="text-sm text-gray-300">Dernière sauvegarde</span>
                        </div>
                        <span class="text-xs text-gray-500">Aujourd'hui 04:00</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection