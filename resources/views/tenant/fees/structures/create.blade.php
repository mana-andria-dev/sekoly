@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center mb-6">
        <a href="{{ route('fees.structures.index') }}" class="text-gray-400 hover:text-white mr-4">
            ← Retour
        </a>
        <h1 class="text-2xl font-bold text-white">Nouvelle structure de frais</h1>
    </div>

    <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
        <form method="POST" action="{{ route('fees.structures.store') }}">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nom *</label>
                    <input type="text" name="name" value="{{ old('name') }}" 
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type *</label>
                    <select name="type" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        <option value="">Sélectionner</option>
                        @foreach($types as $key => $label)
                            <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Année scolaire *</label>
                    <select name="school_year_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                        <option value="">Sélectionner</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" {{ old('school_year_id') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Classe (optionnel)</label>
                    <select name="class_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Toutes les classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Montant (€) *</label>
                    <input type="number" name="amount" value="{{ old('amount') }}" step="0.01"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Mois (pour mensualités)</label>
                    <select name="month" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Sélectionner</option>
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ old('month') == $month ? 'selected' : '' }}>
                                {{ date('F', mktime(0, 0, 0, $month, 1)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Date d'échéance</label>
                    <input type="date" name="due_date" value="{{ old('due_date') }}"
                           class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Description</label>
                    <textarea name="description" rows="3" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">{{ old('description') }}</textarea>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_required" value="1" {{ old('is_required', true) ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-gray-300">Obligatoire</span>
                    </label>
                </div>
                
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="mr-2">
                        <span class="text-gray-300">Actif</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end gap-3 mt-6 pt-6 border-t border-gray-800">
                <a href="{{ route('fees.structures.index') }}" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white">
                    Annuler
                </a>
                <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white">
                    Créer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection