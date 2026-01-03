@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">📤</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Importation d'élèves</h1>
                        <p class="text-gray-400 text-sm mt-1">Importez des élèves depuis un fichier CSV ou Excel</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('students.index', ['tenant' => app('tenant')->name]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
                <a href="{{ route('students.create', ['tenant' => app('tenant')->name]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-lg border border-gray-700 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajouter manuellement
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form (2/3 width) -->
        <div class="lg:col-span-2">
            <!-- Upload Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden card-hover">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                            <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                            Importation du fichier
                        </h2>
                        <span class="text-xs text-gray-500 px-3 py-1 bg-gray-850 rounded-full">CSV ou Excel</span>
                    </div>
                </div>
                
                <!-- Form Body -->
                <div class="p-6">
                    <form action="{{ route('students.import', ['tenant' => app('tenant')->name]) }}" 
                          method="POST" 
                          enctype="multipart/form-data"
                          id="importForm">
                        @csrf
                        
                        <!-- File Upload Area -->
                        <div class="border-2 border-dashed border-gray-700 rounded-xl p-8 text-center hover:border-primary-600 transition-colors duration-200"
                             id="dropArea">
                            <div class="max-w-md mx-auto">
                                <div class="w-16 h-16 mx-auto mb-4 bg-primary-600/10 rounded-full flex items-center justify-center">
                                    <span class="text-primary-600 text-2xl">📁</span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-white mb-2">Glissez-déposez votre fichier</h3>
                                <p class="text-gray-400 text-sm mb-4">ou cliquez pour parcourir</p>
                                
                                <input type="file" 
                                       name="file" 
                                       id="fileInput" 
                                       accept=".csv,.xlsx,.xls"
                                       class="hidden"
                                       required>
                                
                                <label for="fileInput" 
                                       class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg cursor-pointer transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                    </svg>
                                    Choisir un fichier
                                </label>
                                
                                <div class="mt-4" id="fileName"></div>
                                
                                <div class="mt-6 text-xs text-gray-500">
                                    <p>Formats supportés: CSV, XLSX, XLS</p>
                                    <p>Taille maximale: 10MB</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Progress Bar (hidden by default) -->
                        <div id="progressContainer" class="mt-6 hidden">
                            <div class="flex justify-between text-sm text-gray-400 mb-2">
                                <span>Traitement en cours...</span>
                                <span id="progressPercent">0%</span>
                            </div>
                            <div class="w-full bg-gray-800 rounded-full h-2">
                                <div id="progressBar" class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <div id="progressStatus" class="text-xs text-gray-500 mt-2"></div>
                        </div>
                        
                        <!-- Options -->
                        <div class="mt-8 space-y-4">
                            <h4 class="text-sm font-semibold text-gray-300 flex items-center gap-2">
                                <span class="w-2 h-2 bg-info rounded-full"></span>
                                Options d'importation
                            </h4>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center p-3 bg-gray-850/50 rounded-lg">
                                    <input type="checkbox" 
                                           name="has_headers" 
                                           id="hasHeaders" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-700 bg-gray-800 text-primary-600">
                                    <label for="hasHeaders" class="ml-3 text-sm text-gray-300">
                                        Première ligne contient les en-têtes
                                    </label>
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-850/50 rounded-lg">
                                    <input type="checkbox" 
                                           name="skip_duplicates" 
                                           id="skipDuplicates" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-700 bg-gray-800 text-primary-600">
                                    <label for="skipDuplicates" class="ml-3 text-sm text-gray-300">
                                        Ignorer les doublons (email)
                                    </label>
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-850/50 rounded-lg">
                                    <input type="checkbox" 
                                           name="send_welcome_email" 
                                           id="sendWelcomeEmail" 
                                           value="1"
                                           class="rounded border-gray-700 bg-gray-800 text-primary-600">
                                    <label for="sendWelcomeEmail" class="ml-3 text-sm text-gray-300">
                                        Envoyer un email de bienvenue
                                    </label>
                                </div>
                                
                                <div class="flex items-center p-3 bg-gray-850/50 rounded-lg">
                                    <input type="checkbox" 
                                           name="generate_passwords" 
                                           id="generatePasswords" 
                                           value="1" 
                                           checked
                                           class="rounded border-gray-700 bg-gray-800 text-primary-600">
                                    <label for="generatePasswords" class="ml-3 text-sm text-gray-300">
                                        Générer mots de passe automatiques
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Class Selection -->
                            <div class="p-4 bg-gray-850/30 rounded-lg">
                                <label class="block text-sm font-medium text-gray-400 mb-2">
                                    Classe par défaut pour tous les élèves
                                </label>
                                <div class="flex gap-3">
                                    <select name="default_class_id" 
                                            class="flex-1 bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                                        <option value="">Sélectionner une classe</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">
                                                {{ $class->name }}
                                                @if($class->year)
                                                    - {{ $class->year->name }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="text-xs text-gray-500 pt-2">
                                        <span class="text-warning">⚠️</span> Optionnel
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-2">
                                    Si non spécifié, la classe devra être définie dans le fichier d'import
                                </p>
                            </div>
                        </div>
                        
                        <!-- Form Actions -->
                        <div class="mt-8 flex justify-end gap-3">
                            <a href="{{ route('students.index', ['tenant' => app('tenant')->name]) }}" 
                               class="px-5 py-2.5 border border-gray-700 text-gray-300 hover:text-white hover:bg-gray-850 hover:border-gray-600 rounded-lg font-medium transition-all duration-200">
                                Annuler
                            </a>
                            <button type="submit" 
                                    id="submitButton"
                                    class="px-6 py-2.5 bg-gradient-to-r from-primary-600 to-info hover:from-primary-700 hover:to-info/90 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-primary-600/20 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                                </svg>
                                Lancer l'importation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Results Card (initially hidden) -->
            <div id="resultsCard" class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden mt-6 hidden">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-success rounded-full"></div>
                        Résultats de l'importation
                    </h2>
                </div>
                
                <!-- Results Body -->
                <div class="p-6">
                    <div id="resultsContent">
                        <!-- Results will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar (1/3 width) -->
        <div class="space-y-6">
            <!-- Template Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-warning rounded-full"></div>
                        Template de fichier
                    </h3>
                    <span class="text-xs px-2 py-1 bg-warning/10 text-warning rounded-full">Recommandé</span>
                </div>
                
                <div class="space-y-4">
                    <div class="p-4 bg-gray-850/50 rounded-lg">
                        <div class="text-center mb-3">
                            <div class="w-12 h-12 mx-auto mb-2 bg-warning/10 rounded-full flex items-center justify-center">
                                <span class="text-warning text-xl">📥</span>
                            </div>
                            <p class="text-sm text-gray-300">Téléchargez notre template</p>
                        </div>
                        
                        <a href="{{ route('students.import.template', ['tenant' => app('tenant')->name]) }}" 
                           class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-warning/10 hover:bg-warning/20 text-warning font-medium rounded-lg border border-warning/20 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Télécharger template
                        </a>
                        
                        <p class="text-xs text-gray-500 mt-3 text-center">
                            Format correct avec tous les champs
                        </p>
                    </div>
                    
                    <div class="p-4 bg-gray-850/50 rounded-lg">
                        <div class="text-sm text-gray-400 mb-2">Format requis</div>
                        <div class="text-xs text-gray-500 space-y-1">
                            <div class="flex items-center gap-2">
                                <span class="text-green-500">✓</span>
                                <span>Encodage: UTF-8</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-green-500">✓</span>
                                <span>Séparateur: Virgule (CSV)</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="text-green-500">✓</span>
                                <span>Extensions: .csv, .xlsx, .xls</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Instructions Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-sm font-semibold text-gray-300 flex items-center gap-3">
                        <div class="w-2 h-5 bg-info rounded-full"></div>
                        Instructions
                    </h3>
                </div>
                
                <ul class="space-y-3">
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-primary-600/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-primary-600 text-xs font-bold">1</span>
                        </div>
                        <span class="text-sm text-gray-400">
                            Téléchargez le template et remplissez-le avec vos données
                        </span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-success/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-success text-xs font-bold">2</span>
                        </div>
                        <span class="text-sm text-gray-400">
                            Assurez-vous que les emails sont uniques et valides
                        </span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-warning/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-warning text-xs font-bold">3</span>
                        </div>
                        <span class="text-sm text-gray-400">
                            Vérifiez le format des dates (AAAA-MM-JJ)
                        </span>
                    </li>
                    <li class="flex items-start gap-3 p-3 bg-gray-850/30 rounded-lg">
                        <div class="mt-1 w-6 h-6 bg-danger/10 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-danger text-xs font-bold">4</span>
                        </div>
                        <span class="text-sm text-gray-400">
                            Champs obligatoires: Prénom, Nom, Email, Date de naissance
                        </span>
                    </li>
                </ul>
            </div>
            
            <!-- Column Mapping Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 card-hover">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-purple-600 rounded-full"></div>
                    Mapping des colonnes
                </h3>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">first_name</span>
                        <span class="text-xs text-white">Prénom *</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">last_name</span>
                        <span class="text-xs text-white">Nom *</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">email</span>
                        <span class="text-xs text-white">Email *</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">date_of_birth</span>
                        <span class="text-xs text-white">Date naissance *</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">gender</span>
                        <span class="text-xs text-white">Genre (male/female/other)</span>
                    </div>
                    <div class="flex justify-between items-center p-2 bg-gray-850/50 rounded">
                        <span class="text-xs text-gray-400">class_code</span>
                        <span class="text-xs text-white">Code classe</span>
                    </div>
                    <div class="text-center mt-3">
                        <button type="button" 
                                onclick="showAllColumns()"
                                class="text-xs text-primary-400 hover:text-primary-300">
                            Voir tous les champs...
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal for all columns -->
<div id="columnsModal" class="fixed inset-0 bg-black/70 flex items-center justify-center z-50 hidden">
    <div class="bg-gray-900 rounded-xl border border-gray-800 w-full max-w-2xl mx-4 max-h-[80vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-white">Tous les champs d'importation</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-white">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Required Fields -->
                <div class="bg-gray-850/50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                        <span class="text-danger">●</span> Champs obligatoires
                    </h4>
                    <div class="space-y-2">
                        @foreach($requiredColumns as $column => $label)
                        <div class="flex justify-between items-center">
                            <code class="text-xs text-gray-400">{{ $column }}</code>
                            <span class="text-xs text-white">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Optional Fields -->
                <div class="bg-gray-850/50 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                        <span class="text-warning">●</span> Champs optionnels
                    </h4>
                    <div class="space-y-2">
                        @foreach($optionalColumns as $column => $label)
                        <div class="flex justify-between items-center">
                            <code class="text-xs text-gray-400">{{ $column }}</code>
                            <span class="text-xs text-white">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Parent Fields -->
                <div class="bg-gray-850/50 rounded-lg p-4 md:col-span-2">
                    <h4 class="text-sm font-semibold text-white mb-3 flex items-center gap-2">
                        <span class="text-info">●</span> Informations parents
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($parentColumns as $column => $label)
                        <div class="flex justify-between items-center">
                            <code class="text-xs text-gray-400">{{ $column }}</code>
                            <span class="text-xs text-white">{{ $label }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <div class="mt-6 text-center">
                <button onclick="closeModal()" 
                        class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm">
                    Fermer
                </button>
            </div>
        </div>
    </div>
</div>

<style>
#dropArea.drag-over {
    border-color: #3b82f6;
    background-color: rgba(59, 130, 246, 0.05);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dropArea = document.getElementById('dropArea');
    const fileInput = document.getElementById('fileInput');
    const fileName = document.getElementById('fileName');
    const importForm = document.getElementById('importForm');
    const submitButton = document.getElementById('submitButton');
    const progressContainer = document.getElementById('progressContainer');
    const progressBar = document.getElementById('progressBar');
    const progressPercent = document.getElementById('progressPercent');
    const progressStatus = document.getElementById('progressStatus');
    const resultsCard = document.getElementById('resultsCard');
    const resultsContent = document.getElementById('resultsContent');

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop area when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropArea.classList.add('drag-over');
    }

    function unhighlight() {
        dropArea.classList.remove('drag-over');
    }

    // Handle dropped files
    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Handle file input change
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        if (files.length > 0) {
            const file = files[0];
            
            // Validate file type
            const validTypes = ['text/csv', 'application/vnd.ms-excel', 
                               'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'];
            const validExtensions = ['.csv', '.xls', '.xlsx'];
            
            const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
            const isValidType = validTypes.includes(file.type) || 
                               validExtensions.includes(fileExtension);
            
            if (!isValidType) {
                alert('Format de fichier non supporté. Utilisez CSV, XLS ou XLSX.');
                return;
            }
            
            // Validate file size (10MB max)
            if (file.size > 10 * 1024 * 1024) {
                alert('Fichier trop volumineux. Taille maximale: 10MB.');
                return;
            }
            
            // Display file name
            fileName.innerHTML = `
                <div class="inline-flex items-center gap-2 px-3 py-2 bg-gray-850 rounded-lg">
                    <span class="text-green-500">✓</span>
                    <span class="text-sm text-white">${file.name}</span>
                    <span class="text-xs text-gray-500">(${(file.size / 1024 / 1024).toFixed(2)} MB)</span>
                </div>
            `;
            
            // Enable submit button
            submitButton.disabled = false;
        }
    }

    // Handle form submission with AJAX
    importForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Show progress bar
        progressContainer.classList.remove('hidden');
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Importation en cours...
        `;
        
        // Simulate progress updates
        let progress = 0;
        const progressInterval = setInterval(() => {
            progress += 5;
            if (progress > 90) progress = 90;
            progressBar.style.width = progress + '%';
            progressPercent.textContent = progress + '%';
            
            if (progress === 20) progressStatus.textContent = 'Lecture du fichier...';
            if (progress === 40) progressStatus.textContent = 'Validation des données...';
            if (progress === 60) progressStatus.textContent = 'Traitement des élèves...';
            if (progress === 80) progressStatus.textContent = 'Création des comptes...';
        }, 300);
        
        // Send AJAX request
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressPercent.textContent = '100%';
            progressStatus.textContent = 'Importation terminée!';
            
            // Show results
            showResults(data);
            
            // Reset form after 3 seconds
            setTimeout(() => {
                progressContainer.classList.add('hidden');
                submitButton.disabled = false;
                submitButton.innerHTML = `
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                    </svg>
                    Lancer l'importation
                `;
            }, 3000);
        })
        .catch(error => {
            clearInterval(progressInterval);
            alert('Erreur lors de l\'importation: ' + error.message);
            submitButton.disabled = false;
            submitButton.innerHTML = `
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                </svg>
                Lancer l'importation
            `;
        });
    });

    function showResults(data) {
        resultsCard.classList.remove('hidden');
        
        let html = '';
        
        if (data.success) {
            html = `
                <div class="mb-6 bg-green-600/10 border border-green-600/30 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-600/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-white mb-1">Importation réussie!</h3>
                            <p class="text-sm text-gray-300">
                                ${data.message}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="p-4 bg-gray-850/50 rounded-lg">
                        <div class="text-2xl font-bold text-white mb-1">${data.total}</div>
                        <div class="text-sm text-gray-400">Lignes traitées</div>
                    </div>
                    <div class="p-4 bg-gray-850/50 rounded-lg">
                        <div class="text-2xl font-bold text-green-500 mb-1">${data.created}</div>
                        <div class="text-sm text-gray-400">Élèves créés</div>
                    </div>
                    <div class="p-4 bg-gray-850/50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-500 mb-1">${data.skipped}</div>
                        <div class="text-sm text-gray-400">Lignes ignorées</div>
                    </div>
                </div>
            `;
            
            if (data.errors && data.errors.length > 0) {
                html += `
                    <div class="mt-4">
                        <h4 class="text-sm font-semibold text-gray-300 mb-3">Erreurs rencontrées</h4>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                `;
                
                data.errors.forEach(error => {
                    html += `
                        <div class="p-3 bg-red-600/10 border border-red-600/20 rounded-lg">
                            <div class="text-sm text-white mb-1">Ligne ${error.row}: ${error.message}</div>
                            <div class="text-xs text-gray-400">${error.value || ''}</div>
                        </div>
                    `;
                });
                
                html += `</div></div>`;
            }
            
            // Add passwords if generated
            if (data.passwords && data.passwords.length > 0) {
                html += `
                    <div class="mt-6 p-4 bg-blue-600/10 border border-blue-600/30 rounded-lg">
                        <h4 class="text-sm font-semibold text-white mb-3">Mots de passe générés</h4>
                        <div class="text-xs text-gray-400 mb-2">
                            Copiez ces mots de passe avant de fermer cette page:
                        </div>
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                `;
                
                data.passwords.forEach(password => {
                    html += `
                        <div class="flex items-center justify-between p-2 bg-gray-850/50 rounded">
                            <span class="text-sm text-white">${password.email}</span>
                            <div class="flex items-center gap-2">
                                <code class="text-xs text-gray-300">${password.password}</code>
                                <button onclick="copyToClipboard('${password.password}')" 
                                        class="text-xs text-blue-400 hover:text-blue-300">
                                    Copier
                                </button>
                            </div>
                        </div>
                    `;
                });
                
                html += `</div></div>`;
            }
            
        } else {
            html = `
                <div class="mb-6 bg-red-600/10 border border-red-600/30 rounded-xl p-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-red-600/20 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-white mb-1">Erreur d'importation</h3>
                            <p class="text-sm text-gray-300">${data.message}</p>
                        </div>
                    </div>
                </div>
            `;
            
            if (data.errors) {
                html += `<div class="text-sm text-gray-400">${data.errors.join('<br>')}</div>`;
            }
        }
        
        html += `
            <div class="mt-6 flex justify-end gap-3">
                <button onclick="location.reload()" 
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white rounded-lg text-sm">
                    Nouvel import
                </button>
                <a href="${data.download_url || '#'}" 
                   class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm">
                    Télécharger le rapport
                </a>
            </div>
        `;
        
        resultsContent.innerHTML = html;
    }
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        alert('Mot de passe copié!');
    });
}

function showAllColumns() {
    document.getElementById('columnsModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('columnsModal').classList.add('hidden');
}
</script>
@endsection