@extends('tenant.layouts.app')

@section('content')
<div class="mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-white">Ajouter un professeur</h1>
                <p class="text-gray-400 text-sm mt-1">Nouvel enseignant dans l'établissement</p>
            </div>
            <a href="{{ route('teachers.index', ['tenant' => app('tenant')->name]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-gray-800 hover:bg-gray-700 rounded-lg text-sm font-medium text-white transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Retour
            </a>
        </div>
    </div>

    <!-- Messages d'erreur -->
    @if($errors->any())
    <div class="mb-6">
        <div class="bg-red-900/50 border border-red-700 rounded-xl p-4">
            <div class="flex items-center gap-3">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.998-.833-2.732 0L4.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-red-300">Veuillez corriger les erreurs suivantes :</h3>
                    <ul class="mt-2 text-sm text-red-400 list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Formulaire -->
    <form action="{{ route('teachers.store', ['tenant' => app('tenant')->name]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="space-y-6">
            <!-- Informations personnelles -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                    Informations personnelles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Colonne gauche -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nom *</label>
                            <input type="text" name="last_name" required value="{{ old('last_name') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('last_name') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('last_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Prénom *</label>
                            <input type="text" name="first_name" required value="{{ old('first_name') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('first_name') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('first_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date de naissance *</label>
                            <input type="date" name="date_of_birth" required value="{{ old('date_of_birth') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('date_of_birth') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('date_of_birth')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Genre *</label>
                            <select name="gender" required class="w-full bg-gray-800 border {{ $errors->has('gender') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('gender')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Colonne droite -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                            <input type="email" name="email" required value="{{ old('email') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="exemple@ecole.fr">
                            @error('email')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Téléphone</label>
                            <input type="tel" name="phone" value="{{ old('phone') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('phone') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="+33 6 12 34 56 78">
                            @error('phone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Adresse</label>
                            <input type="text" name="address" value="{{ old('address') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('address') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('address')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Photo</label>
                            <input type="file" name="photo" accept="image/*"
                                   class="w-full bg-gray-800 border {{ $errors->has('photo') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('photo')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500">PNG, JPG, JPEG, GIF - Max 2MB</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Informations professionnelles -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-blue-600 rounded-full"></div>
                    Informations professionnelles
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Date d'embauche *</label>
                            <input type="date" name="hire_date" required value="{{ old('hire_date') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('hire_date') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('hire_date')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Type d'emploi *</label>
                            <select name="employment_type" required class="w-full bg-gray-800 border {{ $errors->has('employment_type') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                                <option value="">Sélectionner</option>
                                <option value="CDI" {{ old('employment_type') == 'CDI' ? 'selected' : '' }}>CDI</option>
                                <option value="CDD" {{ old('employment_type') == 'CDD' ? 'selected' : '' }}>CDD</option>
                                <option value="Vacataire" {{ old('employment_type') == 'Vacataire' ? 'selected' : '' }}>Vacataire</option>
                                <option value="Contractuel" {{ old('employment_type') == 'Contractuel' ? 'selected' : '' }}>Contractuel</option>
                            </select>
                            @error('employment_type')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Diplôme académique *</label>
                            <input type="text" name="academic_degree" required value="{{ old('academic_degree') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('academic_degree') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="Ex: Master, Licence, Doctorat...">
                            @error('academic_degree')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Spécialisation *</label>
                            <input type="text" name="specialization" required value="{{ old('specialization') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('specialization') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent"
                                   placeholder="Ex: Mathématiques, Physique, Français...">
                            @error('specialization')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Taux horaire (Ar)</label>
                            <input type="number" step="0.01" name="hourly_rate" value="{{ old('hourly_rate') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('hourly_rate') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('hourly_rate')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Heures/semaine</label>
                            <input type="number" name="hours_per_week" value="{{ old('hours_per_week', 20) }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('hours_per_week') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">
                            @error('hours_per_week')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
               
            </div>
            
            <!-- Informations supplémentaires -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-6">
                <h2 class="text-lg font-semibold text-white mb-6 flex items-center gap-3">
                    <div class="w-2 h-6 bg-green-600 rounded-full"></div>
                    Informations supplémentaires
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Numéro CIN/Passeport</label>
                            <input type="text" name="id_number" value="{{ old('id_number') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('id_number') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('id_number')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Numéro sécurité sociale</label>
                            <input type="text" name="social_security_number" value="{{ old('social_security_number') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('social_security_number') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('social_security_number')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nationalité</label>
                            <input type="text" name="nationality" value="{{ old('nationality') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('nationality') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('nationality')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Ville</label>
                            <input type="text" name="city" value="{{ old('city') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('city') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('city')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Contact d'urgence</label>
                            <input type="text" name="emergency_contact_name" placeholder="Nom"
                                   value="{{ old('emergency_contact_name') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('emergency_contact_name') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white mb-2">
                            @error('emergency_contact_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <input type="tel" name="emergency_contact_phone" placeholder="Téléphone"
                                   value="{{ old('emergency_contact_phone') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('emergency_contact_phone') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('emergency_contact_phone')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <input type="text" name="emergency_contact_relation" placeholder="Relation (Parent, Conjoint...)"
                                   value="{{ old('emergency_contact_relation') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('emergency_contact_relation') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white mt-2">
                            @error('emergency_contact_relation')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Informations bancaires</label>
                            <input type="text" name="bank_name" placeholder="Banque"
                                   value="{{ old('bank_name') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('bank_name') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white mb-2">
                            @error('bank_name')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                            <input type="text" name="bank_account" placeholder="Numéro de compte"
                                   value="{{ old('bank_account') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('bank_account') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('bank_account')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Pays</label>
                            <input type="text" name="country" value="{{ old('country') }}"
                                   class="w-full bg-gray-800 border {{ $errors->has('country') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white">
                            @error('country')
                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- Notes -->
                <div class="mt-6">
                    <label class="block text-sm font-medium text-gray-300 mb-2">Notes</label>
                    <textarea name="notes" rows="3"
                              class="w-full bg-gray-800 border {{ $errors->has('notes') ? 'border-red-500' : 'border-gray-700' }} rounded-lg px-4 py-3 text-white focus:ring-2 focus:ring-primary-600 focus:border-transparent">{{ old('notes') }}</textarea>
                    @error('notes')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Compte utilisateur -->
                <div class="mt-6 p-4 bg-gray-800/50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="create_user_account" id="create_user_account" 
                               value="1" {{ old('create_user_account') ? 'checked' : '' }}
                               class="rounded">
                        <label for="create_user_account" class="text-sm font-medium text-gray-300">
                            Créer un compte utilisateur pour ce professeur
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mt-2 ml-7">
                        Un email d'invitation sera envoyé avec des instructions de connexion.
                    </p>
                    @error('create_user_account')
                    <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <!-- Actions -->
            <div class="flex items-center justify-between">
                <a href="{{ route('teachers.index', ['tenant' => app('tenant')->name]) }}"
                   class="px-6 py-3 bg-gray-800 hover:bg-gray-700 rounded-lg font-medium text-white transition-colors">
                    Annuler
                </a>
                <button type="submit"
                        class="px-6 py-3 bg-primary-600 hover:bg-primary-700 rounded-lg font-medium text-white transition-colors">
                    Enregistrer le professeur
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des matières avec JavaScript
    const subjectCheckboxes = document.querySelectorAll('.subject-checkbox');
    
    subjectCheckboxes.forEach(checkbox => {
        // Initialiser l'état
        const container = checkbox.closest('.subject-item');
        const fields = container.querySelector('.subject-fields');
        const hiddenId = container.querySelector('input[type="hidden"]');
        
        if (!checkbox.checked) {
            container.classList.add('opacity-50');
            fields.style.display = 'none';
            hiddenId.value = '0'; // ID non sélectionné
        } else {
            container.classList.remove('opacity-50');
            fields.style.display = 'flex';
            hiddenId.value = checkbox.dataset.subjectId; // ID sélectionné
        }
        
        // Écouter les changements
        checkbox.addEventListener('change', function() {
            const container = this.closest('.subject-item');
            const fields = container.querySelector('.subject-fields');
            const hiddenId = container.querySelector('input[type="hidden"]');
            
            if (this.checked) {
                container.classList.remove('opacity-50');
                fields.style.display = 'flex';
                hiddenId.value = this.dataset.subjectId;
            } else {
                container.classList.add('opacity-50');
                fields.style.display = 'none';
                hiddenId.value = '0';
                
                // Réinitialiser les champs optionnels
                fields.querySelectorAll('select, input[type="number"], input[type="checkbox"]').forEach(field => {
                    if (field.type === 'checkbox') {
                        field.checked = false;
                    } else if (field.type === 'select-one') {
                        field.value = 'intermediate';
                    } else {
                        field.value = field.type === 'number' ? '0' : '';
                    }
                });
            }
        });
    });

    // Validation en temps réel
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Vérifier si au moins une matière est sélectionnée
            const selectedSubjects = document.querySelectorAll('.subject-checkbox:checked');
            if (selectedSubjects.length === 0) {
                if (!confirm('Aucune matière n\'est sélectionnée. Voulez-vous continuer ?')) {
                    e.preventDefault();
                    return;
                }
            }
            
            // Vérifier les champs requis
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            let firstErrorField = null;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('border-red-500');
                    field.classList.remove('border-gray-700');
                    
                    if (!firstErrorField) {
                        firstErrorField = field;
                    }
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                // Afficher un message d'erreur
                alert('Veuillez remplir tous les champs obligatoires (*)');
                
                // Scroll vers le premier champ en erreur
                if (firstErrorField) {
                    firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstErrorField.focus();
                }
            }
        });
    }
    
    // Réinitialiser les bordures lors de la saisie
    document.querySelectorAll('input, select, textarea').forEach(field => {
        field.addEventListener('input', function() {
            this.classList.remove('border-red-500');
            this.classList.add('border-gray-700');
        });
    });
});
</script>