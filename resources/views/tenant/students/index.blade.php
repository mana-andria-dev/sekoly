@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">👨‍🎓</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Élèves</h1>
                        <p class="text-gray-400 text-sm mt-1">Gestion des élèves de votre établissement</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('students.import.form') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-lg border border-gray-700 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                    </svg>
                    Importer CSV
                </a>
                <a href="{{ route('students.create') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvel élève
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Recherche</label>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}"
                       placeholder="Nom, email, téléphone..."
                       class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
            </div>
            
            <!-- Class filter -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Classe</label>
                <select name="class_id" class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status filter -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Statut</label>
                <select name="status" class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous les statuts</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                    <option value="graduated" {{ request('status') == 'graduated' ? 'selected' : '' }}>Diplômé</option>
                    <option value="transferred" {{ request('status') == 'transferred' ? 'selected' : '' }}>Transféré</option>
                    <option value="expelled" {{ request('status') == 'expelled' ? 'selected' : '' }}>Exclu</option>
                    <option value="left" {{ request('status') == 'left' ? 'selected' : '' }}>Démission</option>
                </select>
            </div>
            
            <!-- Actions -->
            <div class="flex flex-col justify-end gap-2">
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium">
                        Filtrer
                    </button>
                    @if(request()->anyFilled(['search', 'class_id', 'status']))
                    <a href="{{ route('students.index') }}" 
                       class="px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:text-white font-medium">
                        Réinitialiser
                    </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-white">{{ $stats['total'] }}</div>
            <div class="text-sm text-gray-400">Élèves total</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-green-500">{{ $stats['active'] }}</div>
            <div class="text-sm text-gray-400">Actuellement actifs</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-blue-500">{{ $stats['classes'] }}</div>
            <div class="text-sm text-gray-400">Classes occupées</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-purple-500">{{ $stats['new_this_month'] }}</div>
            <div class="text-sm text-gray-400">Nouveaux ce mois</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Élève
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Classe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Contact
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Date de naissance
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Statut
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($students as $student)
                    @php
                        $currentEnrollment = $student->latestEnrollment;
                    @endphp
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <!-- Élève -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-info rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="font-medium text-white">{{ $student->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ ucfirst($student->gender) }}
                                        @if($student->date_of_birth)
                                            • {{ now()->diffInYears($student->date_of_birth) }} ans
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Classe -->
                        <td class="px-6 py-4">
                            @if($currentEnrollment && $currentEnrollment->schoolClass)
                                <div class="flex flex-col">
                                    <div class="inline-flex items-center gap-2">
                                        <span class="px-2 py-1 bg-gray-800 text-gray-300 text-xs rounded-full">
                                            {{ $currentEnrollment->schoolClass->name }}
                                        </span>
                                        @if($currentEnrollment->roll_number)
                                            <span class="text-xs text-gray-400">#{{ $currentEnrollment->roll_number }}</span>
                                        @endif
                                    </div>
                                    @if($currentEnrollment->section)
                                        <span class="text-xs text-gray-500 mt-1">Section {{ $currentEnrollment->section }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-gray-500 text-sm italic">Non inscrit</span>
                            @endif
                        </td>
                        
                        <!-- Contact -->
                        <td class="px-6 py-4">
                            <div class="space-y-1">
                                <div class="text-sm text-gray-300">{{ $student->email }}</div>
                                @if($student->phone)
                                    <div class="text-xs text-gray-500">{{ $student->phone }}</div>
                                @endif
                            </div>
                        </td>
                        
                        <!-- Date de naissance -->
                        <td class="px-6 py-4">
                            @if($student->date_of_birth)
                                <div class="flex flex-col">
                                    <span class="text-sm text-white">
                                        {{ $student->date_of_birth->format('d/m/Y') }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        {{ $student->date_of_birth->age }} ans
                                    </span>
                                </div>
                            @else
                                <span class="text-gray-500 text-sm">Non renseignée</span>
                            @endif
                        </td>
                        
                        <!-- Statut -->
                        <td class="px-6 py-4">
                            @if($currentEnrollment)
                                @php
                                    $statusColors = [
                                        'active' => 'bg-green-600/10 text-green-400 border-green-600/20',
                                        'graduated' => 'bg-blue-600/10 text-blue-400 border-blue-600/20',
                                        'transferred' => 'bg-yellow-600/10 text-yellow-400 border-yellow-600/20',
                                        'expelled' => 'bg-red-600/10 text-red-400 border-red-600/20',
                                        'left' => 'bg-gray-600/10 text-gray-400 border-gray-600/20'
                                    ];
                                    $color = $statusColors[$currentEnrollment->status] ?? $statusColors['active'];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $color }}">
                                    {{ $currentEnrollment->status_label }}
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-600/10 text-gray-400 border border-gray-600/20">
                                    Non inscrit
                                </span>
                            @endif
                        </td>
                        
                        <!-- Actions -->
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('students.show', [
                                            'student' => $student->id
                                        ]) }}" 
                                   class="p-2 text-gray-400 hover:text-blue-400 hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('students.edit', [
                                            'student' => $student->id
                                        ]) }}" 
                                   class="p-2 text-gray-400 hover:text-yellow-400 hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                @if($student->is_active)

                                <form action="{{ route('students.deactivate', [
                                            'student' => $student->id
                                        ]) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Désactiver cet élève ?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors"
                                            title="Désactiver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L6.59 6.59m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    </button>
                                </form>
                                @else
                                <form action="{{ route('students.activate', [
                                            'student' => $student->id
                                        ]) }}" 
                                      method="POST"
                                      onsubmit="return confirm('Réactiver cet élève ?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="p-2 text-gray-400 hover:text-green-400 hover:bg-gray-800 rounded-lg transition-colors"
                                            title="Réactiver">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <!-- Message vide -->
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($students->hasPages())
        <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50">
            {{ $students->links() }}
        </div>
        @endif
    </div>
</div>
@endsection