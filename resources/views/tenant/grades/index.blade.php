{{-- resources/views/tenant/grades/index.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Notes continues</h1>
                <p class="text-gray-400 text-sm mt-1">Gestion des notes, contrôles et évaluations</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('grades.create', app('tenant')->name) }}"
                   class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Nouvelle note
                </a>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Classe</label>
                <select name="class_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Matière</label>
                <select name="subject_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes les matières</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                            {{ $subject->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Période</label>
                <select name="period" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-3 py-2 text-white">
                    <option value="">Toutes</option>
                    <option value="trimester1" {{ request('period') == 'trimester1' ? 'selected' : '' }}>1er Trimestre</option>
                    <option value="trimester2" {{ request('period') == 'trimester2' ? 'selected' : '' }}>2ème Trimestre</option>
                    <option value="trimester3" {{ request('period') == 'trimester3' ? 'selected' : '' }}>3ème Trimestre</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-white">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des notes -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-800">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Élève</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Matière</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Titre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Coef</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-400 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($grades as $grade)
                    <tr class="hover:bg-gray-850">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-white">{{ $grade->student->first_name }} {{ $grade->student->last_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $grade->subject->name }}</td>
                        <td class="px-6 py-4 text-sm text-gray-300">{{ $grade->title ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs rounded-full 
                                @if($grade->grade_type == 'homework') bg-blue-900/50 text-blue-400
                                @elseif($grade->grade_type == 'test') bg-purple-900/50 text-purple-400
                                @elseif($grade->grade_type == 'quiz') bg-green-900/50 text-green-400
                                @else bg-yellow-900/50 text-yellow-400
                                @endif">
                                {{ ucfirst($grade->grade_type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium {{ $grade->score >= ($grade->max_score / 2) ? 'text-green-400' : 'text-red-400' }}">
                                {{ $grade->score }}/{{ $grade->max_score }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $grade->coefficient }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">{{ $grade->grade_date->format('d/m/Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('grades.edit', [app('tenant')->name, $grade->id]) }}"
                                   class="text-gray-400 hover:text-white transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('grades.destroy', [app('tenant')->name, $grade->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-300 transition-colors"
                                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette note ?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            Aucune note trouvée
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($grades->hasPages())
        <div class="px-6 py-4 border-t border-gray-800">
            {{ $grades->withQueryString()->links() }}
        </div>
        @endif
    </div>
</div>
@endsection