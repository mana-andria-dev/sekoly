@extends('layouts.admin')

@section('title', 'Tableau de bord')
@section('subtitle', 'Vue d\'ensemble du système')

@section('content')
<div class="space-y-6">
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Schools -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Total écoles</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $totalSchools }}</p>
                </div>
                <div class="w-12 h-12 bg-primary-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">🏫</span>
                </div>
            </div>
        </div>
        
        <!-- Active Schools -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Écoles actives</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $activeSchools }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✅</span>
                </div>
            </div>
        </div>
        
        <!-- Expiring Soon -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Abonnements expirant</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $expiringSoon }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">⚠️</span>
                </div>
            </div>
        </div>
        
        <!-- Monthly Revenue -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6 card-hover">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-400 text-sm">Revenus du mois</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ number_format($monthlyRevenue, 0, ',', ' ') }} 000 Ariary</p>
                </div>
                <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">💰</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Recent Schools -->
    <div class="bg-gray-900 rounded-xl border border-gray-800">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Dernières écoles inscrites</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Nom</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Téléphone</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date d'inscription</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @foreach($recentSchools as $school)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-primary-600/20 rounded-lg flex items-center justify-center">
                                    <span class="text-sm">{{ substr($school->name, 0, 1) }}</span>
                                </div>
                                <span class="text-white">{{ $school->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-gray-300">{{ $school->email }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $school->phone ?? '-' }}</td>
                        <td class="px-6 py-4 text-gray-300">{{ $school->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.schools.show', $school->id) }}" 
                               class="text-primary-600 hover:text-primary-400 transition-colors">
                                Voir détails
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection