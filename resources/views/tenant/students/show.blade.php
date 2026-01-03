@extends('tenant.layouts.app')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <div class="p-2 bg-gray-850 rounded-lg">
                        <span class="text-xl">👨‍🎓</span>
                    </div>
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-white">Détails de l'élève</h1>
                        <p class="text-gray-400 text-sm mt-1">Fiche complète de {{ $student->name }}</p>
                    </div>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('students.edit', [
                        'tenant' => app('tenant')->name,
                        'student' => $student->id
                    ]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-800 hover:bg-gray-700 text-gray-300 font-medium rounded-lg border border-gray-700 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier
                </a>
                <a href="{{ route('students.index', ['tenant' => app('tenant')->name]) }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-gray-850 hover:bg-gray-800 border border-gray-700 rounded-lg text-sm font-medium text-gray-300 hover:text-white transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Retour
                </a>
            </div>
        </div>
    </div>

    <!-- Grid Layout -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column (2/3 width) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Profile Card -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                            <div class="w-2 h-6 bg-primary-600 rounded-full"></div>
                            Informations personnelles
                        </h2>
                        <div class="flex items-center gap-2">
                            <span class="text-xs px-3 py-1 rounded-full {{ $student->is_active ? 'bg-green-600/10 text-green-500' : 'bg-red-600/10 text-red-500' }}">
                                {{ $student->is_active ? 'Actif' : 'Inactif' }}
                            </span>
                            <span class="text-xs px-3 py-1 bg-gray-800 text-gray-300 rounded-full">
                                {{ ucfirst($student->gender) }}
                            </span>
                        </div>
                    </div>
                </div>                
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="flex flex-col md:flex-row gap-6">
                        <!-- Photo -->
                        <div class="flex-shrink-0">
                            <div class="w-40 h-40 rounded-full bg-gray-850 border-4 border-gray-800 overflow-hidden">
                                @if($student->photo)
                                    <img src="{{ Storage::url($student->photo) }}" 
                                         alt="{{ $student->name }}" 
                                         class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-600 to-info">
                                        <span class="text-white text-4xl font-bold">
                                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1">
                            <div class="mb-6">
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $student->name }}</h3>
                                <div class="flex items-center gap-4 text-gray-400">
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                                        </svg>
                                        <span>{{ $student->email }}</span>
                                    </div>
                                    @if($student->phone)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                        </svg>
                                        <span>{{ $student->phone }}</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Grid Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="p-4 bg-gray-850/50 rounded-lg">
                                    <div class="text-sm text-gray-400 mb-1">Date de naissance</div>
                                    <div class="text-white font-medium">
                                        @if($student->date_of_birth)
                                            {{ $student->date_of_birth->format('d/m/Y') }}
                                            <span class="text-gray-500 text-sm ml-2">
                                                ({{ $student->date_of_birth->age }} ans)
                                            </span>
                                        @else
                                            <span class="text-gray-500">Non renseignée</span>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($student->address)
                                <div class="p-4 bg-gray-850/50 rounded-lg md:col-span-2">
                                    <div class="text-sm text-gray-400 mb-1">Adresse</div>
                                    <div class="text-white font-medium">{{ $student->address }}</div>
                                </div>
                                @endif
                                
                                @if($student->emergency_contact)
                                <div class="p-4 bg-gray-850/50 rounded-lg">
                                    <div class="text-sm text-gray-400 mb-1">Contact d'urgence</div>
                                    <div class="text-white font-medium">{{ $student->emergency_contact }}</div>
                                    @if($student->emergency_relation)
                                        <div class="text-xs text-gray-500 mt-1">{{ $student->emergency_relation }}</div>
                                    @endif
                                </div>
                                @endif
                                
                                <div class="p-4 bg-gray-850/50 rounded-lg">
                                    <div class="text-sm text-gray-400 mb-1">Créé le</div>
                                    <div class="text-white font-medium">
                                        {{ $student->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

			<!-- Informations des parents -->
			<div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
			    <!-- Card Header -->
			    <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
			        <h2 class="text-lg font-semibold text-white flex items-center gap-3">
			            <div class="w-2 h-6 bg-purple-600 rounded-full"></div>
			            Informations des parents
			        </h2>
			    </div>
			    
			    <!-- Card Body -->
			    <div class="p-6">
			        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
			            <!-- Père -->
			            <div class="bg-gray-850/50 rounded-lg p-5">
			                <div class="flex items-center gap-3 mb-4">
			                    <div class="w-10 h-10 bg-blue-600/10 rounded-full flex items-center justify-center">
			                        <span class="text-blue-500 text-lg">👨</span>
			                    </div>
			                    <h3 class="text-md font-semibold text-white">Père</h3>
			                </div>
			                
			                <div class="space-y-4">
			                    @if($student->father_name)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Nom complet</div>
			                        <div class="text-white font-medium">{{ $student->father_name }}</div>
			                    </div>
			                    @endif
			                    
			                    @if($student->father_phone)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Téléphone</div>
			                        <div class="text-white font-medium flex items-center gap-2">
			                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
			                            </svg>
			                            {{ $student->father_phone }}
			                        </div>
			                    </div>
			                    @endif
			                    
			                    @if($student->father_email)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Email</div>
			                        <div class="text-white font-medium flex items-center gap-2">
			                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
			                            </svg>
			                            {{ $student->father_email }}
			                        </div>
			                    </div>
			                    @endif
			                    
			                    @if($student->father_profession)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Profession</div>
			                        <div class="text-white font-medium">{{ $student->father_profession }}</div>
			                    </div>
			                    @endif
			                    
			                    @if($student->father_cin)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">CIN</div>
			                        <div class="text-white font-medium">{{ $student->father_cin }}</div>
			                    </div>
			                    @endif
			                </div>
			            </div>
			            
			            <!-- Mère -->
			            <div class="bg-gray-850/50 rounded-lg p-5">
			                <div class="flex items-center gap-3 mb-4">
			                    <div class="w-10 h-10 bg-pink-600/10 rounded-full flex items-center justify-center">
			                        <span class="text-pink-500 text-lg">👩</span>
			                    </div>
			                    <h3 class="text-md font-semibold text-white">Mère</h3>
			                </div>
			                
			                <div class="space-y-4">
			                    @if($student->mother_name)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Nom complet</div>
			                        <div class="text-white font-medium">{{ $student->mother_name }}</div>
			                    </div>
			                    @endif
			                    
			                    @if($student->mother_phone)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Téléphone</div>
			                        <div class="text-white font-medium flex items-center gap-2">
			                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
			                            </svg>
			                            {{ $student->mother_phone }}
			                        </div>
			                    </div>
			                    @endif
			                    
			                    @if($student->mother_email)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Email</div>
			                        <div class="text-white font-medium flex items-center gap-2">
			                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
			                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
			                            </svg>
			                            {{ $student->mother_email }}
			                        </div>
			                    </div>
			                    @endif
			                    
			                    @if($student->mother_profession)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">Profession</div>
			                        <div class="text-white font-medium">{{ $student->mother_profession }}</div>
			                    </div>
			                    @endif
			                    
			                    @if($student->mother_cin)
			                    <div>
			                        <div class="text-sm text-gray-400 mb-1">CIN</div>
			                        <div class="text-white font-medium">{{ $student->mother_cin }}</div>
			                    </div>
			                    @endif
			                </div>
			            </div>
			        </div>
			        
			        <!-- Tuteur -->
			        @if($student->guardian_name)
			        <div class="mt-6 bg-yellow-600/5 rounded-lg p-5 border border-yellow-600/20">
			            <div class="flex items-center gap-3 mb-4">
			                <div class="w-10 h-10 bg-yellow-600/10 rounded-full flex items-center justify-center">
			                    <span class="text-yellow-500 text-lg">👤</span>
			                </div>
			                <div>
			                    <h3 class="text-md font-semibold text-white">Tuteur</h3>
			                    @if($student->guardian_relation)
			                    <div class="text-xs text-gray-400">
			                        {{ match($student->guardian_relation) {
			                            'grandparent' => 'Grand-parent',
			                            'uncle' => 'Oncle/Tante',
			                            'sibling' => 'Frère/Sœur',
			                            'other_relative' => 'Autre parent',
			                            'legal_guardian' => 'Tuteur légal',
			                            'other' => 'Autre',
			                            default => 'Tuteur'
			                        } }}
			                    </div>
			                    @endif
			                </div>
			            </div>
			            
			            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			                <div>
			                    <div class="text-sm text-gray-400 mb-1">Nom complet</div>
			                    <div class="text-white font-medium">{{ $student->guardian_name }}</div>
			                </div>
			                
			                @if($student->guardian_phone)
			                <div>
			                    <div class="text-sm text-gray-400 mb-1">Téléphone</div>
			                    <div class="text-white font-medium">{{ $student->guardian_phone }}</div>
			                </div>
			                @endif
			                
			                @if($student->guardian_email)
			                <div>
			                    <div class="text-sm text-gray-400 mb-1">Email</div>
			                    <div class="text-white font-medium">{{ $student->guardian_email }}</div>
			                </div>
			                @endif
			                
			                @if($student->guardian_profession)
			                <div>
			                    <div class="text-sm text-gray-400 mb-1">Profession</div>
			                    <div class="text-white font-medium">{{ $student->guardian_profession }}</div>
			                </div>
			                @endif
			                
			                @if($student->guardian_cin)
			                <div>
			                    <div class="text-sm text-gray-400 mb-1">CIN</div>
			                    <div class="text-white font-medium">{{ $student->guardian_cin }}</div>
			                </div>
			                @endif
			            </div>
			        </div>
			        @endif
			    </div>
			</div>            

            <!-- Current Enrollment Card -->
            @if($currentEnrollment)
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-success rounded-full"></div>
                        Inscription scolaire actuelle
                    </h2>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Class Info -->
                        <div class="p-4 bg-gray-850/50 rounded-lg">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-primary-600/10 rounded-lg flex items-center justify-center">
                                    <span class="text-primary-600 text-lg">🏫</span>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400">Classe</div>
                                    <div class="text-white font-bold text-lg">
                                        {{ $currentEnrollment->schoolClass->name ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Année: {{ $currentEnrollment->schoolYear->name ?? 'N/A' }}
                            </div>
                        </div>
                        
                        <!-- Enrollment Info -->
                        <div class="p-4 bg-gray-850/50 rounded-lg">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-info/10 rounded-lg flex items-center justify-center">
                                    <span class="text-info text-lg">📅</span>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400">Date d'inscription</div>
                                    <div class="text-white font-bold text-lg">
                                        {{ $currentEnrollment->enrollment_date->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                Il y a {{ $currentEnrollment->enrollment_date->diffForHumans() }}
                            </div>
                        </div>
                        
                        <!-- Status Info -->
                        <div class="p-4 bg-gray-850/50 rounded-lg">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 bg-success/10 rounded-lg flex items-center justify-center">
                                    <span class="text-success text-lg">📊</span>
                                </div>
                                <div>
                                    <div class="text-sm text-gray-400">Statut</div>
                                    <div class="text-white font-bold text-lg">
                                        {{ $currentEnrollment->status_label }}
                                    </div>
                                </div>
                            </div>
                            <div class="text-xs text-gray-500">
                                @if($currentEnrollment->section)
                                    Section {{ $currentEnrollment->section }}
                                @endif
                                @if($currentEnrollment->roll_number)
                                    • Matricule: {{ $currentEnrollment->roll_number }}
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($currentEnrollment->remarks)
                    <div class="mt-4 p-4 bg-gray-850/30 rounded-lg">
                        <div class="text-sm text-gray-400 mb-2">Remarques</div>
                        <div class="text-gray-300">{{ $currentEnrollment->remarks }}</div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Enrollment History -->
            @if($enrollments->count() > 1)
            <div class="bg-gray-900 border border-gray-800 rounded-xl shadow-xl overflow-hidden">
                <!-- Card Header -->
                <div class="px-6 py-5 border-b border-gray-800 bg-gradient-to-r from-gray-900 to-gray-850">
                    <h2 class="text-lg font-semibold text-white flex items-center gap-3">
                        <div class="w-2 h-6 bg-warning rounded-full"></div>
                        Historique des inscriptions
                    </h2>
                </div>
                
                <!-- Card Body -->
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-850/50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Année</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Classe</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Date</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Statut</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Matricule</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-800">
                                @foreach($enrollments as $enrollment)
                                <tr class="hover:bg-gray-850/30 transition-colors">
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $enrollment->schoolYear->name ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $enrollment->schoolClass->name ?? 'N/A' }}</div>
                                        @if($enrollment->section)
                                            <div class="text-xs text-gray-500">Section {{ $enrollment->section }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3">
                                        <div class="text-sm text-white">{{ $enrollment->enrollment_date->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $enrollment->enrollment_date->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-3">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                            {{ $enrollment->status == 'active' ? 'bg-green-600/10 text-green-400' : '' }}
                                            {{ $enrollment->status == 'graduated' ? 'bg-blue-600/10 text-blue-400' : '' }}
                                            {{ $enrollment->status == 'transferred' ? 'bg-yellow-600/10 text-yellow-400' : '' }}
                                            {{ $enrollment->status == 'expelled' ? 'bg-red-600/10 text-red-400' : '' }}
                                            {{ $enrollment->status == 'left' ? 'bg-gray-600/10 text-gray-400' : '' }}">
                                            {{ $enrollment->status_label }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if($enrollment->roll_number)
                                            <div class="text-sm text-gray-300">{{ $enrollment->roll_number }}</div>
                                        @else
                                            <span class="text-gray-500 text-sm">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column (1/3 width) -->
        <div class="space-y-6">
            <!-- Quick Stats -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-info rounded-full"></div>
                    Statistiques
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-primary-600/10 rounded-lg">
                                <span class="text-primary-600">📚</span>
                            </div>
                            <span class="text-sm text-gray-300">Classes fréquentées</span>
                        </div>
                        <span class="font-bold text-white text-lg">{{ $enrollments->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-success/10 rounded-lg">
                                <span class="text-success">📅</span>
                            </div>
                            <span class="text-sm text-gray-300">Années scolaires</span>
                        </div>
                        <span class="font-bold text-white text-lg">{{ $enrollments->unique('school_year_id')->count() }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-850/50 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-warning/10 rounded-lg">
                                <span class="text-warning">👥</span>
                            </div>
                            <span class="text-sm text-gray-300">Dans la classe</span>
                        </div>
                        <span class="font-bold text-white text-lg">
                            @if($currentEnrollment && $currentEnrollment->schoolClass)
                                {{ $currentEnrollment->schoolClass->students->count() }}
                            @else
                                0
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-success rounded-full"></div>
                    Actions rapides
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('students.edit', [
                            'tenant' => app('tenant')->name,
                            'student' => $student->id
                        ]) }}"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-primary-600/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-primary-600">✏️</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Modifier l'élève</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    
                    @if($currentEnrollment)
                    <a href="{{ route('classes.show', [
                            'tenant' => app('tenant')->name,
                            'schoolClass' => $currentEnrollment->class_id
                        ]) }}"
                       class="flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-success/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-success">🏫</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Voir la classe</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                    @endif
                    
                    <button onclick="window.print()"
                            class="w-full flex items-center justify-between p-3 bg-gray-850/50 hover:bg-gray-800 rounded-lg transition-all duration-200 group">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-800 group-hover:bg-info/20 rounded-lg transition-colors">
                                <span class="text-gray-400 group-hover:text-info">🖨️</span>
                            </div>
                            <span class="text-sm text-gray-300 group-hover:text-white">Imprimer la fiche</span>
                        </div>
                        <svg class="w-4 h-4 text-gray-500 group-hover:text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Emergency Info -->
            @if($student->emergency_contact)
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-danger rounded-full"></div>
                    Urgence
                </h3>
                <div class="space-y-3">
                    <div class="p-3 bg-danger/5 rounded-lg border border-danger/10">
                        <div class="text-sm text-gray-400 mb-1">Contact</div>
                        <div class="text-white font-medium">{{ $student->emergency_contact }}</div>
                        @if($student->emergency_relation)
                            <div class="text-xs text-gray-500 mt-1">{{ $student->emergency_relation }}</div>
                        @endif
                    </div>
                    @if($student->phone)
                    <div class="p-3 bg-gray-850/50 rounded-lg">
                        <div class="text-sm text-gray-400 mb-1">Téléphone</div>
                        <div class="text-white font-medium flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $student->phone }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- System Info -->
            <div class="bg-gray-900 border border-gray-800 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-300 mb-4 flex items-center gap-3">
                    <div class="w-2 h-5 bg-gray-600 rounded-full"></div>
                    Informations système
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center p-3 bg-gray-850/50 rounded-lg">
                        <span class="text-sm text-gray-400">ID</span>
                        <code class="text-xs text-gray-300 bg-gray-800 px-2 py-1 rounded">{{ $student->id }}</code>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-850/50 rounded-lg">
                        <span class="text-sm text-gray-400">Créé le</span>
                        <span class="text-sm text-gray-300">{{ $student->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 bg-gray-850/50 rounded-lg">
                        <span class="text-sm text-gray-400">Dernière modification</span>
                        <span class="text-sm text-gray-300">{{ $student->updated_at->format('d/m/Y') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Print Styles -->
<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        background: white !important;
        color: black !important;
    }
    
    .bg-gray-900, .bg-gray-850, .bg-gray-800 {
        background: white !important;
        border: 1px solid #ccc !important;
    }
    
    .text-white, .text-gray-300 {
        color: black !important;
    }
    
    .text-gray-400, .text-gray-500 {
        color: #666 !important;
    }
}
</style>
@endsection