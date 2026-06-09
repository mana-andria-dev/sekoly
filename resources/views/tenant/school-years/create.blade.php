{{-- resources/views/tenant/school-years/create.blade.php --}}
@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Nouvelle année scolaire</h1>
                <p class="text-gray-400 text-sm mt-1">Créez une nouvelle année scolaire avec ses périodes</p>
            </div>
            <a href="{{ route('school-years.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
        <form action="{{ route('school-years.store') }}" method="POST">
            @csrf

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Nom de l'année scolaire *</label>
                        <input type="text" 
                               name="name" 
                               value="{{ old('name') }}"
                               class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                               placeholder="2025-2026"
                               required>
                        <p class="mt-1 text-xs text-gray-500">Format: AAAA-AAAA (ex: 2025-2026)</p>
                        @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Type de période *</label>
                        <select name="period_type_id" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600" required>
                            <option value="">Sélectionner un type</option>
                            @foreach($periodTypes as $type)
                                <option value="{{ $type->id }}" {{ old('period_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->period_count }} périodes)
                                </option>
                            @endforeach
                        </select>
                        @error('period_type_id')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Aperçu des périodes -->
                <div class="bg-gray-800/50 rounded-lg p-4">
                    <h3 class="text-sm font-medium text-gray-300 mb-3">Aperçu des périodes</h3>
                    <div id="periods-preview" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-2">
                        <div class="text-gray-500 text-sm">Sélectionnez un type de période pour voir l'aperçu</div>
                    </div>
                </div>

                <div class="bg-yellow-900/20 border border-yellow-800 rounded-lg p-4">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                        </svg>
                        <div>
                            <h3 class="text-sm font-medium text-yellow-400">Information</h3>
                            <p class="text-xs text-yellow-300 mt-1">
                                L'année scolaire débutera le 1er septembre et se terminera le 31 juillet.
                                Les périodes seront créées automatiquement selon le type sélectionné.
                                L'année créée deviendra automatiquement l'année active.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('school-years.index') }}"
                       class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                        Annuler
                    </a>
                    <button type="submit"
                            class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                        Créer l'année scolaire
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const periodTypeSelect = document.querySelector('select[name="period_type_id"]');
    const periodsPreview = document.getElementById('periods-preview');
    
    const periodNames = {
        1: ['1er Trimestre', '2ème Trimestre', '3ème Trimestre'],
        2: ['1er Semestre', '2ème Semestre'],
        3: ['Période 1', 'Période 2', 'Période 3', 'Période 4']
    };
    
    function updatePreview(periodCount, typeName) {
        let periods = [];
        
        if (typeName && typeName.toLowerCase().includes('trimestre')) {
            periods = ['1er Trimestre', '2ème Trimestre', '3ème Trimestre'];
        } else if (typeName && typeName.toLowerCase().includes('semestre')) {
            periods = ['1er Semestre', '2ème Semestre'];
        } else {
            for (let i = 1; i <= periodCount; i++) {
                periods.push(`Période ${i}`);
            }
        }
        
        if (periods.length === 0) {
            periodsPreview.innerHTML = '<div class="text-gray-500 text-sm">Sélectionnez un type de période pour voir l\'aperçu</div>';
            return;
        }
        
        let html = '';
        periods.forEach((period, index) => {
            html += `
                <div class="flex items-center gap-2 text-sm">
                    <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-gray-300">${period}</span>
                </div>
            `;
        });
        
        periodsPreview.innerHTML = html;
    }
    
    periodTypeSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const periodCount = selectedOption.text.match(/\d+/);
        const typeName = selectedOption.text;
        
        if (periodCount) {
            updatePreview(parseInt(periodCount[0]), typeName);
        }
    });
});
</script>
@endpush
@endsection