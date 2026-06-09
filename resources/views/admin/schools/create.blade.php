@extends('layouts.admin')

@section('title', 'Ajouter une école')
@section('subtitle', 'Création d\'un nouvel établissement')

@section('content')
<div class="bg-gray-900 rounded-xl border border-gray-800 overflow-hidden">
    <div class="p-6 border-b border-gray-800">
        <h3 class="text-lg font-semibold text-white">Nouvelle école</h3>
    </div>
    
    <form method="POST" action="{{ route('admin.schools.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="p-6 space-y-6">
            <!-- Nom de l'école -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Nom de l'école *</label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name') }}"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email *</label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email') }}"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Téléphone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">Téléphone</label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       value="{{ old('phone') }}"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('phone') border-red-500 @enderror">
                @error('phone')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Adresse -->
            <div>
                <label for="address" class="block text-sm font-medium text-gray-300 mb-2">Adresse</label>
                <textarea 
                    name="address" 
                    id="address" 
                    rows="3"
                    class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 @error('address') border-red-500 @enderror"
                >{{ old('address') }}</textarea>
                @error('address')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Logo -->
            <div>
                <label for="logo" class="block text-sm font-medium text-gray-300 mb-2">Logo</label>
                <input type="file" 
                       name="logo" 
                       id="logo" 
                       accept="image/*"
                       class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600">
                @error('logo')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
            </div>
            
            <!-- Sous-domaine -->
            <div>
                <label for="subdomain" class="block text-sm font-medium text-gray-300 mb-2">Sous-domaine *</label>
                <div class="flex">
                    <input type="text" 
                           name="subdomain" 
                           id="subdomain" 
                           value="{{ old('subdomain') }}"
                           placeholder="mon-ecole"
                           class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg text-white focus:outline-none focus:border-primary-600 @error('subdomain') border-red-500 @enderror"
                           required>
                    <span class="px-4 py-2 bg-gray-700 border border-gray-700 rounded-r-lg text-gray-300">.site.test</span>
                </div>
                @error('subdomain')
                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-400">Utilisez uniquement des lettres, chiffres et tirets</p>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-800 bg-gray-850 flex justify-end gap-3">
            <a href="{{ route('admin.schools.index') }}" 
               class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg text-white font-medium transition-colors">
                Annuler
            </a>
            <button type="submit" 
                    class="px-4 py-2 bg-primary-600 hover:bg-primary-700 rounded-lg text-white font-medium transition-colors">
                Créer l'école
            </button>
        </div>
    </form>
</div>
@endsection