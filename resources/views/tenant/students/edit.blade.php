@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-white mb-2">Modifier l'élève</h1>
        <p class="text-gray-400">Mettez à jour les informations de {{ $student->name }}</p>
    </div>

    <!-- Formulaire de suppression (placé AVANT le formulaire de MAJ) -->
    <div class="mb-6 flex justify-end">
        <form action="{{ route('students.destroy', ['student' => $student->id]) }}" 
              method="POST" 
              id="deleteForm"
              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet élève ? Cette action est irréversible.')">
            @csrf
            @method('DELETE')
            <button type="submit" 
                    class="px-6 py-3 border border-danger/30 text-danger hover:bg-danger/10 rounded-lg font-medium transition-colors">
                Supprimer l'élève
            </button>
        </form>
    </div>

    <!-- Formulaire de mise à jour -->
    <form action="{{ route('students.update', ['student' => $student->id]) }}" 
          method="POST" 
          enctype="multipart/form-data"
          class="space-y-6">
        @csrf
        @method('PUT')
        
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
                            @if($student->photo)
                                <img src="{{ Storage::url($student->photo) }}" 
                                     alt="{{ $student->name }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="text-gray-500 text-center">
                                    <span class="text-4xl">👤</span>
                                    <p class="text-xs mt-1">Aucune photo</p>
                                </div>
                            @endif
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
                    <p class="text-gray-300 mb-2">Téléchargez une nouvelle photo ou conservez l'actuelle</p>
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
                    @if($student->photo)
                        <div class="mt-3">
                            <label class="inline-flex items-center">
                                <input type="checkbox" name="remove_photo" value="1" class="rounded border-gray-700 bg-gray-800 text-primary-600">
                                <span class="ml-2 text-sm text-gray-400">Supprimer la photo actuelle</span>
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Informations personnelles -->
        <div class="bg-gray-900 rounded-xl border border-gray-800 p-6">
            <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span>👤</span> Informations personnelles
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Prénom *</label>
                    <input type="text" 
                           name="first_name" 
                           value="{{ old('first_name', $student->first_name) }}"
                           required
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Nom *</label>
                    <input type="text" 
                           name="last_name" 
                           value="{{ old('last_name', $student->last_name) }}"
                           required
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Email *</label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $student->email) }}"
                           required
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone', $student->phone) }}"
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Date de naissance *</label>
                    <input type="date" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth', $student->date_of_birth_formatted ?? '') }}"
                           required
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Genre *</label>
                    <select name="gender" 
                            required
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner...</option>
                        <option value="male" {{ old('gender', $student->gender) == 'male' ? 'selected' : '' }}>Masculin</option>
                        <option value="female" {{ old('gender', $student->gender) == 'female' ? 'selected' : '' }}>Féminin</option>
                        <option value="other" {{ old('gender', $student->gender) == 'other' ? 'selected' : '' }}>Autre</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Contact d'urgence</label>
                    <input type="text" 
                           name="emergency_contact" 
                           value="{{ old('emergency_contact', $student->emergency_contact) }}"
                           placeholder="Nom et téléphone"
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Relation d'urgence</label>
                    <input type="text" 
                           name="emergency_relation" 
                           value="{{ old('emergency_relation', $student->emergency_relation) }}"
                           placeholder="Père, mère, tuteur..."
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-400 mb-2">Adresse complète</label>
                    <textarea name="address" 
                              rows="2"
                              placeholder="Rue, ville, code postal"
                              class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">{{ old('address', $student->address) }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Statut</label>
                    <select name="is_active" 
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="1" {{ old('is_active', $student->is_active) == 1 ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active', $student->is_active) == 0 ? 'selected' : '' }}>Inactif</option>
                    </select>
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
                               value="{{ old('father_name', $student->father_name) }}"
                               placeholder="Nom et prénom du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="father_phone" 
                               value="{{ old('father_phone', $student->father_phone) }}"
                               placeholder="Téléphone du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="father_email" 
                               value="{{ old('father_email', $student->father_email) }}"
                               placeholder="Email du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="father_profession" 
                               value="{{ old('father_profession', $student->father_profession) }}"
                               placeholder="Profession du père"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="father_cin" 
                               value="{{ old('father_cin', $student->father_cin) }}"
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
                               value="{{ old('mother_name', $student->mother_name) }}"
                               placeholder="Nom et prénom de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="mother_phone" 
                               value="{{ old('mother_phone', $student->mother_phone) }}"
                               placeholder="Téléphone de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="mother_email" 
                               value="{{ old('mother_email', $student->mother_email) }}"
                               placeholder="Email de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="mother_profession" 
                               value="{{ old('mother_profession', $student->mother_profession) }}"
                               placeholder="Profession de la mère"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="mother_cin" 
                               value="{{ old('mother_cin', $student->mother_cin) }}"
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
                               value="{{ old('guardian_name', $student->guardian_name) }}"
                               placeholder="Nom et prénom du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Relation</label>
                        <select name="guardian_relation" 
                                class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                            <option value="">Sélectionner la relation</option>
                            <option value="grandparent" {{ old('guardian_relation', $student->guardian_relation) == 'grandparent' ? 'selected' : '' }}>Grand-parent</option>
                            <option value="uncle" {{ old('guardian_relation', $student->guardian_relation) == 'uncle' ? 'selected' : '' }}>Oncle/Tante</option>
                            <option value="sibling" {{ old('guardian_relation', $student->guardian_relation) == 'sibling' ? 'selected' : '' }}>Frère/Sœur</option>
                            <option value="other_relative" {{ old('guardian_relation', $student->guardian_relation) == 'other_relative' ? 'selected' : '' }}>Autre parent</option>
                            <option value="legal_guardian" {{ old('guardian_relation', $student->guardian_relation) == 'legal_guardian' ? 'selected' : '' }}>Tuteur légal</option>
                            <option value="other" {{ old('guardian_relation', $student->guardian_relation) == 'other' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Téléphone</label>
                        <input type="text" 
                               name="guardian_phone" 
                               value="{{ old('guardian_phone', $student->guardian_phone) }}"
                               placeholder="Téléphone du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Email</label>
                        <input type="email" 
                               name="guardian_email" 
                               value="{{ old('guardian_email', $student->guardian_email) }}"
                               placeholder="Email du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">Profession</label>
                        <input type="text" 
                               name="guardian_profession" 
                               value="{{ old('guardian_profession', $student->guardian_profession) }}"
                               placeholder="Profession du tuteur"
                               class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-400 mb-2">CIN</label>
                        <input type="text" 
                               name="guardian_cin" 
                               value="{{ old('guardian_cin', $student->guardian_cin) }}"
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
                    <label class="block text-sm font-medium text-gray-400 mb-2">Année scolaire</label>
                    <select name="school_year_id" 
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner une année</option>
                        @foreach($schoolYears as $year)
                            <option value="{{ $year->id }}" 
                                    {{ old('school_year_id', $currentEnrollment->school_year_id ?? '') == $year->id ? 'selected' : '' }}>
                                {{ $year->name }}
                                @if($year->is_active)
                                    <span class="text-green-500">(Active)</span>
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Classe -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Classe</label>
                    <select name="class_id" 
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner une classe</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" 
                                    data-year="{{ $class->school_year_id }}"
                                    {{ old('class_id', $currentEnrollment->class_id ?? '') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                                @if($class->year)
                                    - {{ $class->year->name }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <!-- Section -->
                {{--
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Section</label>
                    <select name="section" 
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="">Sélectionner...</option>
                        <option value="A" {{ old('section', $currentEnrollment->section ?? '') == 'A' ? 'selected' : '' }}>Section A</option>
                        <option value="B" {{ old('section', $currentEnrollment->section ?? '') == 'B' ? 'selected' : '' }}>Section B</option>
                        <option value="C" {{ old('section', $currentEnrollment->section ?? '') == 'C' ? 'selected' : '' }}>Section C</option>
                        <option value="D" {{ old('section', $currentEnrollment->section ?? '') == 'D' ? 'selected' : '' }}>Section D</option>
                        <option value="E" {{ old('section', $currentEnrollment->section ?? '') == 'E' ? 'selected' : '' }}>Section E</option>
                    </select>
                </div>
                --}}
                
                <!-- Numéro de matricule -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Numéro de matricule</label>
                    <input type="text" 
                           name="roll_number" 
                           value="{{ old('roll_number', $currentEnrollment->roll_number ?? '') }}"
                           placeholder="Numéro de matricule"
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <!-- Date d'inscription -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Date d'inscription</label>
                    <input type="date" 
                           name="enrollment_date" 
                           value="{{ old('enrollment_date', $currentEnrollment ? $currentEnrollment->enrollment_date->format('Y-m-d') : date('Y-m-d')) }}"
                           class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white placeholder-gray-500 focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                </div>
                
                <!-- Statut d'inscription (si vous voulez le modifier) -->
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Statut d'inscription</label>
                    <select name="enrollment_status" 
                            class="w-full bg-gray-850 border border-gray-700 rounded-lg px-4 py-2.5 text-white focus:border-primary-600 focus:ring-1 focus:ring-primary-600">
                        <option value="active" {{ old('enrollment_status', $currentEnrollment->status ?? 'active') == 'active' ? 'selected' : '' }}>Actif</option>
                        <option value="graduated" {{ old('enrollment_status', $currentEnrollment->status ?? '') == 'graduated' ? 'selected' : '' }}>Diplômé</option>
                        <option value="transferred" {{ old('enrollment_status', $currentEnrollment->status ?? '') == 'transferred' ? 'selected' : '' }}>Transféré</option>
                        <option value="expelled" {{ old('enrollment_status', $currentEnrollment->status ?? '') == 'expelled' ? 'selected' : '' }}>Exclu</option>
                        <option value="left" {{ old('enrollment_status', $currentEnrollment->status ?? '') == 'left' ? 'selected' : '' }}>Démission</option>
                    </select>
                </div>
            </div>
            
            <!-- Information sur l'inscription actuelle -->
            @if($currentEnrollment)
            <div class="mt-6 p-4 bg-gray-850/50 rounded-lg border border-gray-700">
                <h3 class="text-sm font-medium text-gray-300 mb-2">Inscription actuelle</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">Classe:</span>
                        <span class="text-white ml-2">{{ $currentEnrollment->schoolClass->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Année:</span>
                        <span class="text-white ml-2">{{ $currentEnrollment->schoolYear->name ?? 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Inscrit le:</span>
                        <span class="text-white ml-2">{{ $currentEnrollment->enrollment_date->format('d/m/Y') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Statut:</span>
                        <span class="ml-2 px-2 py-1 text-xs rounded-full 
                            {{ $currentEnrollment->status == 'active' ? 'bg-green-600/10 text-green-400' : '' }}
                            {{ $currentEnrollment->status == 'graduated' ? 'bg-blue-600/10 text-blue-400' : '' }}
                            {{ $currentEnrollment->status == 'transferred' ? 'bg-yellow-600/10 text-yellow-400' : '' }}
                            {{ $currentEnrollment->status == 'expelled' ? 'bg-red-600/10 text-red-400' : '' }}
                            {{ $currentEnrollment->status == 'left' ? 'bg-gray-600/10 text-gray-400' : '' }}">
                            {{ $currentEnrollment->status_label }}
                        </span>
                    </div>
                </div>
            </div>
            @endif
        </div>        
        
        <!-- Actions -->
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('students.index') }}" 
               class="px-6 py-3 border border-gray-700 text-gray-300 hover:text-white hover:border-gray-600 rounded-lg font-medium transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-lg font-medium transition-colors">
                Mettre à jour
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

// Empêcher la soumission accidentelle du formulaire de suppression
document.addEventListener('DOMContentLoaded', function() {
    const deleteForm = document.getElementById('deleteForm');
    const updateForm = document.querySelector('form[method="POST"]:not(#deleteForm)');
    
    if (deleteForm && updateForm) {
        // S'assurer que le formulaire de suppression ne fait pas partie du formulaire de MAJ
        deleteForm.style.display = 'block';
    }
});
</script>

<script>
// Filtrer les classes par année scolaire sélectionnée
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.querySelector('select[name="school_year_id"]');
    const classSelect = document.querySelector('select[name="class_id"]');
    const classOptions = Array.from(classSelect.querySelectorAll('option[data-year]'));
    
    // Fonction pour filtrer les classes
    function filterClasses() {
        const selectedYear = yearSelect.value;
        
        // Masquer toutes les options de classe
        classOptions.forEach(option => {
            option.style.display = 'none';
        });
        
        // Afficher l'option vide
        const defaultOption = classSelect.querySelector('option[value=""]');
        if (defaultOption) defaultOption.style.display = 'block';
        
        // Afficher les classes correspondant à l'année sélectionnée
        if (selectedYear) {
            const matchingClasses = classOptions.filter(option => 
                option.getAttribute('data-year') === selectedYear
            );
            
            matchingClasses.forEach(option => {
                option.style.display = 'block';
            });
            
            // Si aucune classe ne correspond
            if (matchingClasses.length === 0) {
                if (defaultOption) {
                    defaultOption.textContent = 'Aucune classe disponible pour cette année';
                }
            }
            
            // Si une classe est déjà sélectionnée mais ne correspond pas, la désélectionner
            const selectedOption = classSelect.querySelector('option:checked');
            if (selectedOption && selectedOption.value && selectedOption.getAttribute('data-year') !== selectedYear) {
                classSelect.value = '';
            }
        } else {
            // Si aucune année n'est sélectionnée, afficher toutes les classes
            classOptions.forEach(option => {
                option.style.display = 'block';
            });
            if (defaultOption) {
                defaultOption.textContent = 'Sélectionner une classe';
            }
        }
    }
    
    // Appliquer le filtre au chargement
    filterClasses();
    
    // Écouter les changements
    if (yearSelect) {
        yearSelect.addEventListener('change', filterClasses);
    }
});

</script>
@endsection