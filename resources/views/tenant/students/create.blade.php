@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto">
    <!-- Afficher les erreurs globales -->
    @if($errors->any())
    <div class="mb-6 bg-danger/10 border border-danger/30 rounded-xl p-4 animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-danger/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-danger" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-medium text-white mb-1">Erreurs de validation</h3>
                <ul class="text-sm text-gray-300 space-y-1">
                    @foreach($errors->all() as $error)
                    <li class="flex items-center gap-2">
                        <svg class="w-3 h-3 text-danger flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        {{ $error }}
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Afficher l'alerte de mot de passe temporaire -->
    @if(session('show_password_alert'))
    <div class="mb-6 bg-green-600/10 border border-green-600/30 rounded-xl p-4 animate-fade-in">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-green-600/20 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="flex-1">
                <h3 class="font-medium text-white mb-1">Élève créé avec succès</h3>
                <p class="text-sm text-gray-300 mb-2">
                    L'élève a été créé avec le mot de passe temporaire suivant:
                </p>
                <div class="bg-gray-900 rounded-lg p-3 mb-3">
                    <div class="flex items-center justify-between">
                        <code class="text-lg font-mono text-white bg-gray-800 px-3 py-1 rounded">
                            {{ session('temp_password') }}
                        </code>
                        <button type="button" 
                                onclick="copyPassword('{{ session('temp_password') }}')"
                                class="text-sm text-primary-400 hover:text-primary-300">
                            Copier
                        </button>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">
                        Email: {{ session('student_email') }}
                    </p>
                </div>
                <p class="text-sm text-gray-400">
                    <span class="text-yellow-400">⚠️ Important:</span> Ce mot de passe doit être changé à la première connexion.
                </p>
            </div>
        </div>
    </div>
    @endif

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white mb-2">Ajouter un nouvel élève</h1>
        <p class="text-gray-400">Remplissez les informations de l'élève et son inscription</p>
    </div>

    <form action="{{ route('students.store') }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        
        <!-- Photo de profil -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span>📸</span> Photo de profil
            </h2>
            
            <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
                <!-- Preview de l'image -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div id="photoPreview" 
                             class="w-32 h-32 rounded-full bg-gray-850 border-2 border-gray-700 flex items-center justify-center overflow-hidden">
                            <div class="text-gray-500 text-center">
                                <span class="text-4xl">👤</span>
                                <p class="text-xs mt-1">Aucune photo</p>
                            </div>
                        </div>
                        <input type="file" 
                               name="photo" 
                               id="photoInput" 
                               accept="image/*" 
                               class="hidden"
                               onchange="previewPhoto(event)">
                        <label for="photoInput" 
                               class="absolute bottom-0 right-0 w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center cursor-pointer hover:bg-primary-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                    </div>
                </div>
                
                <!-- Instructions -->
                <div class="flex-1">
                    <p class="text-gray-300 mb-2">Téléchargez une photo de profil</p>
                    <ul class="text-sm text-gray-400 space-y-1">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Format : JPG, PNG, GIF</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Taille maximale : 5MB</span>
                        </li>
                    </ul>
                    <!-- Afficher l'erreur pour la photo -->
                    @error('photo')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span>👤</span> Informations personnelles
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Prénom -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Prénom *</label>
                    <input type="text" 
                           name="first_name" 
                           value="{{ old('first_name') }}"
                           required
                           class="w-full bg-gray-850 border {{ $errors->has('first_name') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('first_name')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Nom -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nom *</label>
                    <input type="text" 
                           name="last_name" 
                           value="{{ old('last_name') }}"
                           required
                           class="w-full bg-gray-850 border {{ $errors->has('last_name') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('last_name')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Email -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Email *</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           required
                           class="w-full bg-gray-850 border {{ $errors->has('email') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('email')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Téléphone -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full bg-gray-850 border {{ $errors->has('phone') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('phone')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Date de naissance -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Date de naissance *</label>
                    <input type="date" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth') }}"
                           required
                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                           class="w-full bg-gray-850 border {{ $errors->has('date_of_birth') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('date_of_birth')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Genre -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Genre *</label>
                    <select name="gender" 
                            required
                            class="w-full bg-gray-850 border {{ $errors->has('gender') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner...</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Masculin</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Féminin</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                    @error('gender')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Contact d'urgence -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Contact d'urgence</label>
                    <input type="text" 
                           name="emergency_contact" 
                           value="{{ old('emergency_contact') }}"
                           placeholder="Nom et téléphone"
                           class="w-full bg-gray-850 border {{ $errors->has('emergency_contact') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('emergency_contact')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Relation d'urgence -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Relation d'urgence</label>
                    <input type="text" 
                           name="emergency_relation" 
                           value="{{ old('emergency_relation') }}"
                           placeholder="Père, mère, tuteur..."
                           class="w-full bg-gray-850 border {{ $errors->has('emergency_relation') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('emergency_relation')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Adresse complète</label>
                    <textarea name="address" 
                              rows="2"
                              placeholder="Rue, ville, code postal"
                              class="w-full bg-gray-850 border {{ $errors->has('address') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Informations des parents -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span>👨‍👩‍👧‍👦</span> Informations des parents
            </h2>
            
            <!-- Père -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-blue-600/10 rounded-full flex items-center justify-center">
                        <span class="text-blue-500 text-lg">👨</span>
                    </div>
                    <h3 class="text-md font-semibold text-white">Père</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nom complet</label>
                        <input type="text" 
                               name="father_name" 
                               value="{{ old('father_name') }}"
                               placeholder="Nom et prénom du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="father_phone" 
                               value="{{ old('father_phone') }}"
                               placeholder="Téléphone du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="father_email" 
                               value="{{ old('father_email') }}"
                               placeholder="Email du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="father_profession" 
                               value="{{ old('father_profession') }}"
                               placeholder="Profession du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="father_cin" 
                               value="{{ old('father_cin') }}"
                               placeholder="Carte d'identité nationale"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>
            </div>
            
            <!-- Mère -->
            <div class="mb-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-8 h-8 bg-pink-600/10 rounded-full flex items-center justify-center">
                        <span class="text-pink-500 text-lg">👩</span>
                    </div>
                    <h3 class="text-md font-semibold text-white">Mère</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nom complet</label>
                        <input type="text" 
                               name="mother_name" 
                               value="{{ old('mother_name') }}"
                               placeholder="Nom et prénom de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="mother_phone" 
                               value="{{ old('mother_phone') }}"
                               placeholder="Téléphone de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="mother_email" 
                               value="{{ old('mother_email') }}"
                               placeholder="Email de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="mother_profession" 
                               value="{{ old('mother_profession') }}"
                               placeholder="Profession de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="mother_cin" 
                               value="{{ old('mother_cin') }}"
                               placeholder="Carte d'identité nationale"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>
            </div>
            
            <!-- Tuteur (optionnel) -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-yellow-600/10 rounded-full flex items-center justify-center">
                            <span class="text-yellow-500 text-lg">👤</span>
                        </div>
                        <h3 class="text-md font-semibold text-white">Tuteur (optionnel)</h3>
                    </div>
                    <span class="text-xs text-gray-500 px-3 py-1 bg-gray-800 rounded-full">Si différent des parents</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Nom complet</label>
                        <input type="text" 
                               name="guardian_name" 
                               value="{{ old('guardian_name') }}"
                               placeholder="Nom et prénom du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Relation</label>
                        <select name="guardian_relation" 
                                class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <option value="">Sélectionner la relation</option>
                            <option value="grandparent" {{ old('guardian_relation') == 'grandparent' ? 'selected' : '' }}>Grand-parent</option>
                            <option value="uncle" {{ old('guardian_relation') == 'uncle' ? 'selected' : '' }}>Oncle/Tante</option>
                            <option value="sibling" {{ old('guardian_relation') == 'sibling' ? 'selected' : '' }}>Frère/Sœur</option>
                            <option value="other_relative" {{ old('guardian_relation') == 'other_relative' ? 'selected' : '' }}>Autre parent</option>
                            <option value="legal_guardian" {{ old('guardian_relation') == 'legal_guardian' ? 'selected' : '' }}>Tuteur légal</option>
                            <option value="other" {{ old('guardian_relation') == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="guardian_phone" 
                               value="{{ old('guardian_phone') }}"
                               placeholder="Téléphone du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="guardian_email" 
                               value="{{ old('guardian_email') }}"
                               placeholder="Email du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="guardian_profession" 
                               value="{{ old('guardian_profession') }}"
                               placeholder="Profession du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="guardian_cin" 
                               value="{{ old('guardian_cin') }}"
                               placeholder="Carte d'identité nationale"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                </div>
            </div>
        </div>        
        
        <!-- Inscription scolaire -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span>🏫</span> Inscription scolaire
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Année scolaire -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Année scolaire *</label>
                    <select name="school_year_id" 
                            required
                            class="w-full bg-gray-850 border {{ $errors->has('school_year_id') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner une année</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" 
                                    {{ old('school_year_id', $currentYear->id ?? '') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                                @if($year->is_active)
                                    <span class="text-green-500">(Active)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('school_year_id')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Classe -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Classe *</label>
                    <select name="class_id" 
                            required
                            class="w-full bg-gray-850 border {{ $errors->has('class_id') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                    data-year="{{ $class->school_year_id }}"
                                    {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if($class->year)
                                    - {{ $class->year->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('class_id')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Date d'inscription -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Date d'inscription *</label>
                    <input type="date" 
                           name="enrollment_date" 
                           value="{{ old('enrollment_date', date('Y-m-d')) }}"
                           required
                           max="{{ date('Y-m-d') }}"
                           class="w-full bg-gray-850 border {{ $errors->has('enrollment_date') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('enrollment_date')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Numéro de matricule -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Numéro de matricule</label>
                    <input type="text" 
                           name="roll_number" 
                           value="{{ old('roll_number') }}"
                           placeholder="Généré automatiquement si vide"
                           class="w-full bg-gray-850 border {{ $errors->has('roll_number') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    @error('roll_number')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <!-- Section -->
                {{--
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Section</label>
                    <select name="section" 
                            class="w-full bg-gray-850 border {{ $errors->has('section') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner...</option>
                        <option value="A" {{ old('section') == 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ old('section') == 'B' ? 'selected' : '' }}>Section B</option>
                        <option value="C" {{ old('section') == 'C' ? 'selected' : '' }}>Section C</option>
                        <option value="D" {{ old('section') == 'D' ? 'selected' : '' }}>Section D</option>
                        <option value="E" {{ old('section') == 'E' ? 'selected' : '' }}>Section E</option>
                    </select>
                    @error('section')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                --}}
                
                <!-- Remarques -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Remarques</label>
                    <textarea name="remarks" 
                              rows="2"
                              placeholder="Informations supplémentaires..."
                              class="w-full bg-gray-850 border {{ $errors->has('remarks') ? 'border-danger' : 'border-gray-700' }} rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">{{ old('remarks') }}</textarea>
                    @error('remarks')
                        <div class="mt-2 flex items-center gap-2 text-sm text-danger">
                            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </div>
                    @enderror
                </div>
            </div>
        </div>
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('students.index') }}" 
               class="px-6 py-3 border border-gray-700 text-gray-300 hover:text-white hover:border-gray-600 rounded-lg font-medium transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                Créer l'élève
            </button>
        </div>
    </form>
</div>

<script>
function previewPhoto(event) {
    const reader = new FileReader();
    const preview = document.getElementById('photoPreview');
    
    reader.onload = function() {
        preview.innerHTML = `<img src="${reader.result}" class="w-full h-full object-cover" alt="Preview">`;
    }
    
    if (event.target.files[0]) {
        reader.readAsDataURL(event.target.files[0]);
    }
}

function copyPassword(password) {
    navigator.clipboard.writeText(password).then(function() {
        alert('Mot de passe copié !');
    }, function(err) {
        console.error('Erreur lors de la copie: ', err);
    });
}

// Filtrer les classes par année scolaire sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.querySelector('select[name="school_year_id"]');
    const classSelect = document.querySelector('select[name="class_id"]');
    const classOptions = Array.from(classSelect.querySelectorAll('option[data-year]'));
    
    function filterClasses() {
        const selectedYear = yearSelect.value;
        
        // Masquer toutes les options de classe
        classOptions.forEach(option => {
            option.style.display = 'none';
        });
        
        // Afficher les options correspondant à l'année sélectionnée
        if (selectedYear) {
            const matchingClasses = classOptions.filter(option => 
                option.getAttribute('data-year') === selectedYear
            );
            
            matchingClasses.forEach(option => {
                option.style.display = 'block';
            });
            
            // Si aucune classe ne correspond, afficher un message
            if (matchingClasses.length === 0) {
                const defaultOption = classSelect.querySelector('option[value=""]');
                if (defaultOption) {
                    defaultOption.textContent = 'Aucune classe disponible pour cette année';
                }
            } else {
                // Réinitialiser la sélection si elle ne correspond pas
                const selectedOption = classSelect.querySelector('option:checked');
                if (selectedOption && selectedOption.getAttribute('data-year') !== selectedYear) {
                    classSelect.value = '';
                }
            }
        } else {
            // Si aucune année n'est sélectionnée, afficher toutes les classes
            classOptions.forEach(option => {
                option.style.display = 'block';
            });
        }
    }
    
    if (yearSelect) {
        yearSelect.addEventListener('change', filterClasses);
        // Appliquer le filtre au chargement si une année est déjà sélectionnée
        // filterClasses();
    }
});
</script>
@endsection