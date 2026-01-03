@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">🔗</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Affectations</h1>
                        <p class="text-gray-400 text-sm mt-1">Gestion des affectations Classe - Matière - Professeur</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="/assignments/create"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle affectation
                </a>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-gray-900 border border-gray-800 rounded-xl p-4 card-hover">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Class filter -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Classe</label>
                <select name="class_id" class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }} ({{ $class->year->name ?? 'N/A' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Subject filter -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Matière</label>
                <select name="subject_id" class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les matières</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Teacher filter -->
            <div>
                <label class="block text-xs font-medium text-gray-400 mb-2">Professeur</label>
                <select name="teacher_id" class="w-full bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Tous les professeurs</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}" {{ request('teacher_id') == $teacher->id ? 'selected' : '' }}>
                            {{ $teacher->first_name }} {{ $teacher->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <!-- Status & Actions -->
            <div class="flex flex-col justify-end gap-2">
                <div class="flex items-center gap-2">
                    <select name="active" class="flex-1 bg-gray-850 border border-gray-700 rounded-lg px-3 py-2 text-white">
                        <option value="">Tous statuts</option>
                        <option value="1" {{ request('active') == '1' ? 'selected' : '' }}>Actives</option>
                        <option value="0" {{ request('active') == '0' ? 'selected' : '' }}>Inactives</option>
                    </select>
                </div>
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium">
                        Filtrer
                    </button>
                    <a href="/assignments" 
                       class="px-4 py-2 border border-gray-700 rounded-lg text-gray-300 hover:text-white font-medium">
                        Réinitialiser
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-white">{{ $assignments->total() }}</div>
            <div class="text-sm text-gray-400">Affectations totales</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-green-500">
                {{ $assignments->where('is_active', true)->count() }}
            </div>
            <div class="text-sm text-gray-400">Affectations actives</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-blue-500">{{ $classes->count() }}</div>
            <div class="text-sm text-gray-400">Classes concernées</div>
        </div>
        <div class="bg-gray-900 border border-gray-800 rounded-xl p-4">
            <div class="text-2xl font-bold text-purple-500">{{ $teachers->count() }}</div>
            <div class="text-sm text-gray-400">Professeurs assignés</div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden shadow-xl">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Classe
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Matière
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Professeur
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Heures/Sem
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                            Coef.
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
                    @forelse($assignments as $assignment)
                    <tr class="hover:bg-gray-850/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $assignment->schoolClass->name }}</div>
                            <div class="text-xs text-gray-500">{{ $assignment->schoolClass->year->name ?? 'N/A' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $assignment->subject->name }}</div>
                            <div class="text-xs text-gray-500">{{ $assignment->subject->code }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($assignment->teacher)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-success/10 rounded-full flex items-center justify-center">
                                    <span class="text-success text-xs">👨‍🏫</span>
                                </div>
                                <div>
                                    <div class="text-sm text-white">{{ $assignment->teacher->first_name }} {{ $assignment->teacher->last_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->teacher->email }}</div>
                                </div>
                            </div>
                            @else
                            <span class="text-gray-500 text-sm italic">Non assigné</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-600/10 text-blue-400">
                                {{ $assignment->hours_per_week }}h
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-600/10 text-purple-400">
                                {{ $assignment->coefficient }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <form action="{{ route('assignments.toggle-active', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}"
                                method="POST"
                                class="inline">
                                @csrf
                                <button type="submit" 
                                        onclick="return confirm('Changer le statut de cette affectation ?')"
                                        class="relative inline-flex items-center h-6 rounded-full w-11 transition-colors {{ $assignment->is_active ? 'bg-green-600' : 'bg-gray-700' }}">
                                    <span class="sr-only">Toggle status</span>
                                    <span class="inline-block w-4 h-4 transform bg-white rounded-full transition-transform {{ $assignment->is_active ? 'translate-x-6' : 'translate-x-1' }}"></span>
                                </button>
                            </form>
                            <span class="ml-2 text-sm {{ $assignment->is_active ? 'text-green-400' : 'text-gray-400' }}">
                                {{ $assignment->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('assignments.show', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}"
                                   class="p-2 text-gray-400 hover:text-blue-400 hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Voir détails">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('assignments.edit', [
                                    'tenant' => app('tenant')->name,
                                    'assignment' => $assignment->id
                                ]) }}"
                                   class="p-2 text-gray-400 hover:text-yellow-400 hover:bg-gray-800 rounded-lg transition-colors"
                                   title="Modifier">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('assignments.destroy', [
                                        'tenant' => app('tenant')->name,
                                        'assignment' => $assignment->id
                                    ]) }}"
                                    method="POST"
                                    class="inline">
                                    
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette affectation ?')"
                                        class="p-2 text-gray-400 hover:text-red-400 hover:bg-gray-800 rounded-lg transition-colors"
                                        title="Supprimer">
                                        
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7
                                                m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-lg font-medium text-gray-300 mb-2">Aucune affectation trouvée</p>
                                <p class="text-sm text-gray-500 mb-4">
                                    @if(request()->anyFilled(['class_id', 'subject_id', 'teacher_id', 'active']))
                                        Essayez de modifier vos critères de recherche
                                    @else
                                        Commencez par créer votre première affectation
                                    @endif
                                </p>
                                <a href="/assignments/create"
                                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Créer une affectation
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($assignments->hasPages())
        <div class="px-6 py-4 border-t border-gray-800 bg-gray-900/50">
            {{ $assignments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection