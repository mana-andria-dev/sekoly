@extends('tenant.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-2xl font-bold text-white">Gestion des documents</h1>
            <p class="text-gray-400 mt-1">Gérez tous les documents des élèves</p>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 p-4 mb-6">
        <form method="GET" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Élève</label>
                    <select name="student_id" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Tous les élèves</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ request('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->first_name }} {{ $student->last_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Type de document</label>
                    <select name="document_type" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Tous les types</option>
                        @foreach(\App\Models\StudentDocument::TYPES as $key => $label)
                            <option value="{{ $key }}" {{ request('document_type') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Statut</label>
                    <select name="status" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        <option value="">Tous les statuts</option>
                        @foreach(\App\Models\StudentDocument::STATUSES as $key => $label)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                        Filtrer
                    </button>
                    @if(request('student_id') || request('document_type') || request('status'))
                        <a href="{{ route('documents.index') }}" 
                           class="bg-gray-700 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Liste des documents -->
    <div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-850">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Document</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Élève</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Taille</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Généré le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Expire le</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-800">
                    @forelse($documents as $document)
                    <tr class="hover:bg-gray-850 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-600/10 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-white font-medium">{{ $document->title }}</div>
                                    <div class="text-xs text-gray-500">{{ $document->file_name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-primary-600/20 rounded-full flex items-center justify-center">
                                    <span class="text-white text-xs">
                                        {{ substr($document->student->first_name ?? '', 0, 1) }}{{ substr($document->student->last_name ?? '', 0, 1) }}
                                    </span>
                                </div>
                                <span class="text-white">
                                    {{ $document->student->first_name }} {{ $document->student->last_name }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-300 text-sm">{{ $document->type_label }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-gray-400 text-sm">{{ $document->formatted_file_size }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-white text-sm">{{ $document->generated_at->format('d/m/Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $document->generated_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @if($document->expires_at)
                                <div class="text-white text-sm">{{ $document->expires_at->format('d/m/Y') }}</div>
                                @if($document->is_expired)
                                    <span class="text-xs text-red-400">Expiré</span>
                                @elseif($document->expires_at->diffInDays(now()) <= 30)
                                    <span class="text-xs text-yellow-400">Expire bientôt</span>
                                @endif
                            @else
                                <span class="text-gray-500 text-sm">Permanent</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-600/20 text-gray-400',
                                    'published' => 'bg-green-600/20 text-green-400',
                                    'archived' => 'bg-yellow-600/20 text-yellow-400',
                                    'expired' => 'bg-red-600/20 text-red-400',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs {{ $statusColors[$document->status] ?? 'bg-gray-600/20 text-gray-400' }}">
                                {{ $document->status_label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex gap-2">
                                <a href="{{ route('documents.preview', $document->id) }}" 
                                   target="_blank"
                                   class="p-1.5 text-blue-400 hover:text-blue-300 transition-colors"
                                   title="Aperçu">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('documents.download', $document->id) }}" 
                                   class="p-1.5 text-green-400 hover:text-green-300 transition-colors"
                                   title="Télécharger">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                </a>
                                <button type="button"
                                        onclick="openStatusModal({{ $document->id }}, '{{ $document->status }}')"
                                        class="p-1.5 text-yellow-400 hover:text-yellow-300 transition-colors"
                                        title="Changer statut">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                                <button type="button"
                                        onclick="confirmDelete({{ $document->id }}, '{{ $document->title }}')"
                                        class="p-1.5 text-red-400 hover:text-red-300 transition-colors"
                                        title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <svg class="w-16 h-16 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg">Aucun document trouvé</p>
                                <p class="text-sm text-gray-500">Générez des documents depuis la fiche d'un élève</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="mt-6">
        {{ $documents->links() }}
    </div>
</div>

<!-- Modal Changer Statut -->
<div id="statusModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-gray-900 rounded-xl border border-gray-800 w-full max-w-md mx-4">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Changer le statut</h3>
        </div>
        <form id="statusForm" method="POST">
            @csrf
            @method('PATCH')
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Nouveau statut</label>
                    <select name="status" class="w-full px-3 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white">
                        @foreach(\App\Models\StudentDocument::STATUSES as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="p-6 border-t border-gray-800 flex justify-end gap-3">
                <button type="button" onclick="closeStatusModal()" 
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors">
                    Annuler
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition-colors">
                    Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Suppression -->
<div id="deleteModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 hidden items-center justify-center">
    <div class="bg-gray-900 rounded-xl border border-gray-800 w-full max-w-md mx-4">
        <div class="p-6 border-b border-gray-800">
            <h3 class="text-lg font-semibold text-white">Confirmer la suppression</h3>
        </div>
        <div class="p-6">
            <p class="text-gray-300">Êtes-vous sûr de vouloir supprimer le document <strong id="deleteDocumentTitle"></strong> ?</p>
            <p class="text-sm text-red-400 mt-2">Cette action est irréversible.</p>
        </div>
        <div class="p-6 border-t border-gray-800 flex justify-end gap-3">
            <button type="button" onclick="closeDeleteModal()" 
                    class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-gray-300 rounded-lg transition-colors">
                Annuler
            </button>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
</div>

<script>
function openStatusModal(documentId, currentStatus) {
    const modal = document.getElementById('statusModal');
    const form = document.getElementById('statusForm');
    form.action = `/documents/${documentId}/status`;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeStatusModal() {
    const modal = document.getElementById('statusModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function confirmDelete(documentId, documentTitle) {
    const modal = document.getElementById('deleteModal');
    const form = document.getElementById('deleteForm');
    const titleSpan = document.getElementById('deleteDocumentTitle');
    form.action = `/documents/${documentId}`;
    titleSpan.textContent = documentTitle;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Fermer les modals en cliquant en dehors
document.getElementById('statusModal').addEventListener('click', function(e) {
    if (e.target === this) closeStatusModal();
});
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) closeDeleteModal();
});
</script>

<style>
@media print {
    .no-print {
        display: none !important;
    }
}
</style>
@endsection