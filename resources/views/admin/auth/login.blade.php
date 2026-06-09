<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sekoly - Connexion</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        primary: {
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                    },
                }
            }
        }
    </script>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-950">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full">
            <!-- Logo -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-primary-600 to-blue-600 rounded-2xl shadow-lg mb-4">
                    <span class="text-white font-bold text-2xl">S</span>
                </div>
                <h2 class="text-2xl font-bold text-white">Sekoly Admin</h2>
                <p class="text-gray-400 mt-2">Connectez-vous à votre espace d'administration</p>
            </div>
            
            <!-- Login Form -->
            <div class="bg-gray-900 rounded-xl border border-gray-800 p-8">
                @if($errors->any())
                    <div class="mb-6 bg-red-600/10 border border-red-600/30 rounded-lg p-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-red-600/20 rounded-full flex items-center justify-center">
                                <svg class="w-4 h-4 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-red-400">{{ $errors->first() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
                
                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    
                    <div class="mb-5">
                        <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               value="{{ old('email') }}"
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 transition-colors"
                               placeholder="admin@sekoly.com"
                               required>
                    </div>
                    
                    <div class="mb-6">
                        <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Mot de passe</label>
                        <input type="password" 
                               name="password" 
                               id="password" 
                               class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white focus:outline-none focus:border-primary-600 transition-colors"
                               placeholder="••••••••"
                               required>
                    </div>
                    
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember" class="w-4 h-4 bg-gray-800 border-gray-700 rounded focus:ring-primary-600">
                            <span class="ml-2 text-sm text-gray-400">Se souvenir de moi</span>
                        </label>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-400 transition-colors">
                            Mot de passe oublié ?
                        </a>
                    </div>
                    
                    <button type="submit" 
                            class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 rounded-lg transition-colors duration-200">
                        Se connecter
                    </button>
                </form>
            </div>
            
            <!-- Back to site -->
            <div class="text-center mt-6">
                <a href="{{ url('/') }}" class="text-gray-400 hover:text-white text-sm transition-colors">
                    ← Retour au site
                </a>
            </div>
        </div>
    </div>
</body>
</html>