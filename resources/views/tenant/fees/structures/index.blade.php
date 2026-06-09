@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-white">Structures de frais</h1>
        <a href="{{ route('fees.structures.create') }}" 
           class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
            + Nouvelle structure
        </a>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Année scolaire</label>
                <select name="school_year_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                    <option value="">Toutes les années</option>
                    @foreach($schoolYears as $year)
                        <option value="{{ $year->id }}" {{ $schoolYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Classe</label>
                <select name="class_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                    <option value="">Toutes les classes</option>
                    @foreach($classes as $class)
                        <option value="{{ $class->id }}" {{ $classId == $class->id ? 'selected' : '' }}>
                            {{ $class->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des structures -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-850">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Nom</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Classe</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Montant</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Statut</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-800">
                @forelse($feeStructures as $structure)
                <tr class="hover:bg-gray-850 transition-colors">
                    <td class="px-6 py-4 text-white">{{ $structure->name }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 bg-blue-600/20 text-blue-400 rounded-full text-xs">
                            {{ ucfirst($structure->type) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-300">{{ $structure->class?->name ?? 'Toutes classes' }}</td>
                    <td class="px-6 py-4 text-white">{{ number_format($structure->amount, 0, ',', ' ') }} €</td>
                    <td class="px-6 py-4">
                        @if($structure->is_active)
                            <span class="px-2 py-1 bg-green-600/20 text-green-400 rounded-full text-xs">Actif</span>
                        @else
                            <span class="px-2 py-1 bg-red-600/20 text-red-400 rounded-full text-xs">Inactif</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-3">
                            <a href="{{ route('fees.structures.edit', $structure) }}" class="text-yellow-400 hover:text-yellow-300">Modifier</a>
                            <form method="POST" action="{{ route('fees.structures.destroy', $structure) }}" 
                                  onsubmit="return confirm('Supprimer cette structure ?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-400 hover:text-red-300">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                        Aucune structure de frais trouvée
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <div class="mt-6">
        {{ $feeStructures->links() }}
    </div>
</div>
@endsection