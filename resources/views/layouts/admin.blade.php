<!DOCTYPE html>
<html lang="fr" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Sekoly | Gestion centrale</title>
    
    <!-- Tailwind CSS via CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        gray: {
                            950: '#0a0a0f',
                            900: '#111827',
                            850: '#1a1f2e',
                            800: '#1f2937',
                            750: '#252f3f',
                            700: '#374151',
                            600: '#4b5563',
                            500: '#6b7280',
                            400: '#9ca3af',
                            300: '#d1d5db',
                            200: '#e5e7eb',
                            100: '#f3f4f6',
                        },
                        primary: {
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        success: '#10b981',
                        warning: '#f59e0b',
                        danger: '#ef4444',
                        info: '#3b82f6'
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.3s ease-in-out',
                        'slide-in': 'slideIn 0.3s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideIn: {
                            '0%': { transform: 'translateX(-10px)', opacity: '0' },
                            '100%': { transform: 'translateX(0)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            overflow: hidden;
        }
        .sidebar-icon {
            transition: all 0.2s ease;
        }
        .sidebar-link:hover .sidebar-icon {
            transform: translateX(3px);
        }
        .card-hover {
            transition: all 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
        html, body {
            height: 100%;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-950 text-gray-100">
    <div class="h-screen flex flex-col">
        <div class="flex flex-1 overflow-hidden">
            <!-- Sidebar -->
            <aside class="w-64 bg-gray-900 border-r border-gray-800 flex flex-col flex-shrink-0 overflow-y-auto">
                <!-- Logo -->
                <div class="p-6 border-b border-gray-800 flex-shrink-0">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary-600 to-info rounded-lg flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-lg">S</span>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-white truncate">Sekoly Admin</h2>
                            <p class="text-xs text-gray-400 mt-1">Gestion centrale</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Menu -->
                <nav class="flex-1 p-5 overflow-y-auto">
                    <ul class="space-y-1.5">
                        <li>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 transition-all duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-gray-850 text-white' : '' }}">
                                <div class="sidebar-icon w-5 h-5 flex items-center justify-center">
                                    <span class="text-lg">📊</span>
                                </div>
                                <span class="font-medium">Tableau de bord</span>
                            </a>
                        </li>

                        <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">
                            Gestion des écoles
                        </li>

                        <li>
                            <a href="{{ route('admin.schools.index') }}" 
                               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 transition-all duration-200 group {{ request()->routeIs('admin.schools.*') ? 'bg-gray-850 text-white' : '' }}">
                                <div class="sidebar-icon w-5 h-5 flex items-center justify-center">
                                    <span class="text-lg">🏫</span>
                                </div>
                                <span class="font-medium">Écoles</span>
                                @php
                                    $totalSchools = \App\Models\Tenant::count();
                                @endphp
                                @if($totalSchools > 0)
                                <span class="ml-auto px-2 py-0.5 text-xs bg-primary-600/20 text-primary-400 rounded-full">
                                    {{ $totalSchools }}
                                </span>
                                @endif
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('admin.subscriptions.index') }}" 
                               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 transition-all duration-200 group {{ request()->routeIs('admin.subscriptions.*') ? 'bg-gray-850 text-white' : '' }}">
                                <div class="sidebar-icon w-5 h-5 flex items-center justify-center">
                                    <span class="text-lg">💳</span>
                                </div>
                                <span class="font-medium">Abonnements</span>
                            </a>
                        </li>

                        <li class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-4">
                            Administration
                        </li>

                        <li>
                            <a href="{{ route('users.index') }}" 
                               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 transition-all duration-200 group {{ request()->routeIs('admin.users.*') ? 'bg-gray-850 text-white' : '' }}">
                                <div class="sidebar-icon w-5 h-5 flex items-center justify-center">
                                    <span class="text-lg">👥</span>
                                </div>
                                <span class="font-medium">Administrateurs</span>
                            </a>
                        </li>

                        <li>
                            <a href="#" 
                               class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-lg text-gray-300 hover:text-white hover:bg-gray-850 transition-all duration-200 group {{ request()->routeIs('settings') ? 'bg-gray-850 text-white' : '' }}">
                                <div class="sidebar-icon w-5 h-5 flex items-center justify-center">
                                    <span class="text-lg">⚙️</span>
                                </div>
                                <span class="font-medium">Paramètres</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                <!-- Logout Section -->
                <div class="p-5 border-t border-gray-800 flex-shrink-0">
                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-gray-850 hover:bg-danger/10 text-danger hover:text-danger border border-gray-800 hover:border-danger/30 rounded-lg font-medium transition-all duration-200 group">
                            <div class="sidebar-icon group-hover:rotate-180 transition-transform duration-200">
                                <span class="text-lg">🚪</span>
                            </div>
                            <span>Déconnexion</span>
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                <!-- Navbar -->
                <nav class="bg-gray-900 border-b border-gray-800 px-6 py-4 flex-shrink-0">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-xl font-semibold text-white">@yield('title', 'Dashboard')</h1>
                            <p class="text-sm text-gray-400 mt-1">@yield('subtitle', 'Panel d\'administration central')</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <!-- User Info -->
                            <div class="flex items-center gap-3 px-4 py-2 bg-gray-850 rounded-lg">
                                <div class="w-8 h-8 bg-gradient-to-br from-primary-600 to-info rounded-full flex items-center justify-center">
                                    <span class="text-white font-semibold text-sm">
                                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                                    </span>
                                </div>
                                <div class="hidden md:block">
                                    <p class="text-sm font-semibold text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                                    <p class="text-xs text-gray-400">Super Administrateur</p>
                                </div>
                            </div>

                            <!-- Logout Button -->
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit" 
                                        class="px-4 py-2 border border-gray-700 hover:border-danger text-gray-400 hover:text-danger font-medium rounded-lg transition-all duration-200 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </nav>

                <!-- Main Content (Scrollable) -->
                <main class="flex-1 overflow-y-auto">
                    <div class="p-6">
                        <!-- Flash Messages -->
                        @if(session('success'))
                        <div class="mb-6 bg-green-600/10 border border-green-600/30 rounded-xl p-4 animate-fade-in">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-green-600/20 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-300">{{ session('success') }}</p>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endif

                        @if(session('error'))
                        <div class="mb-6 bg-red-600/10 border border-red-600/30 rounded-xl p-4 animate-fade-in">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-red-600/20 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293-1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-300">{{ session('error') }}</p>
                                </div>
                                <button onclick="this.parentElement.parentElement.remove()" class="text-gray-400 hover:text-white">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        @endif

                        @yield('content')
                    </div>
                </main>
            </div>
        </div>
    </div>
    @stack('scripts')
</body>
</html>