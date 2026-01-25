<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Project Command Center</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                        },
                        dark: {
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }

        /* Modern Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #334155;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Active Nav Glow Effect */
        .nav-active {
            background: linear-gradient(90deg, rgba(79, 70, 229, 0.1) 0%, rgba(79, 70, 229, 0) 100%);
            border-left: 3px solid #6366f1;
            color: #6366f1 !important;
        }

        .dark .nav-active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.15) 0%, rgba(99, 102, 241, 0) 100%);
            color: #818cf8 !important;
        }
    </style>
</head>

<body class="bg-gray-50 dark:bg-[#0f172a] text-slate-800 dark:text-slate-200 antialiased transition-colors duration-300"
    x-data="{ 
        sidebarOpen: false, 
        darkMode: localStorage.getItem('theme') === 'dark',
        toggleTheme() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('theme', this.darkMode ? 'dark' : 'light');
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }"
    x-init="$watch('darkMode', val => val ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark')); if(darkMode) document.documentElement.classList.add('dark');">

    <div class="flex h-screen overflow-hidden">

        <div x-show="sidebarOpen" @click="sidebarOpen = false" x-transition.opacity
            class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden"></div>

        <aside
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white dark:bg-slate-800 border-r border-slate-200 dark:border-slate-700 transition-transform duration-300 transform lg:static lg:translate-x-0 shadow-2xl lg:shadow-none"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

            <div class="flex items-center justify-center h-20 border-b border-slate-100 dark:border-slate-700/50">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                    <div
                        class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-white font-bold text-lg shadow-lg shadow-indigo-500/30 group-hover:scale-110 transition-transform duration-300">
                        J
                    </div>
                    <span
                        class="text-xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-slate-800 to-slate-600 dark:from-white dark:to-slate-400">
                        Jasa Digital<span class="font-light"> UMKM</span>
                    </span>
                </a>
            </div>

            <nav class="mt-6 px-4 space-y-1.5 overflow-y-auto h-[calc(100vh-5rem)]">

                <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">Menu</p>

                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative overflow-hidden
                   {{ request()->routeIs('dashboard') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('dashboard') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                        </path>
                    </svg>
                    {{ __('messages.dashboard') }}
                </a>

                <a href="{{ route('projects.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('projects.*') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('projects.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                    </svg>
                    {{ __('messages.projects') }}
                </a>

                <a href="{{ route('clients.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('clients.*') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('clients.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    {{ __('messages.clients') }}
                </a>

                <a href="{{ route('freelancers.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative
                 {{ request()->routeIs('freelancers.*') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('freelancers.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    {{ __('messages.freelancers') }}
                </a>

                <a href="{{ route('invoices.index') }}"
                    class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative
                   {{ request()->routeIs('invoices.*') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                    <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('invoices.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                    {{ __('messages.invoices') }}
                </a>

                @if(auth()->user()->isAdmin())
                    <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mt-4 mb-2">Admin</p>
                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 group relative
                       {{ request()->routeIs('admin.users.*') ? 'nav-active' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700/50 hover:text-slate-900 dark:hover:text-white' }}">
                        <svg class="w-5 h-5 mr-3 transition-colors {{ request()->routeIs('admin.users.*') ? 'text-indigo-500 dark:text-indigo-400' : 'text-slate-400 group-hover:text-indigo-500' }}"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        User Management
                    </a>
                @endif

            </nav>
        </aside>

        <div class="flex-1 flex flex-col overflow-hidden relative">

            <header
                class="flex justify-between items-center py-4 px-6 bg-white/80 dark:bg-slate-800/80 backdrop-blur-md sticky top-0 z-30 border-b border-slate-200 dark:border-slate-700/50 transition-colors duration-300">

                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-slate-500 hover:text-indigo-600 focus:outline-none lg:hidden transition-colors active:scale-90">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h2 class="text-lg font-semibold text-slate-800 dark:text-white hidden sm:block">
                        {{-- Logic to show current page title could go here --}}
                        Dashboard
                    </h2>
                </div>

                <div class="flex items-center space-x-3 sm:space-x-5">

                    <div
                        class="flex items-center bg-slate-100 dark:bg-slate-700 rounded-full p-1 border border-slate-200 dark:border-slate-600">
                        <a href="{{ route('lang.switch', 'id') }}"
                            class="px-2 py-1 rounded-full transition-all duration-300 {{ App::getLocale() == 'id' ? 'bg-white dark:bg-slate-600 shadow-sm scale-105' : 'opacity-50 hover:opacity-100' }}">
                            <img src="https://flagcdn.com/w20/id.png" width="20" alt="ID" class="rounded-sm">
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}"
                            class="px-2 py-1 rounded-full transition-all duration-300 {{ App::getLocale() == 'en' ? 'bg-white dark:bg-slate-600 shadow-sm scale-105' : 'opacity-50 hover:opacity-100' }}">
                            <img src="https://flagcdn.com/w20/gb.png" width="20" alt="EN" class="rounded-sm">
                        </a>
                    </div>

                    <button @click="toggleTheme()"
                        class="relative p-2 text-slate-500 hover:text-indigo-500 dark:text-slate-400 dark:hover:text-yellow-400 transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 focus:outline-none active:scale-95">
                        <svg x-show="!darkMode" x-transition:enter="transition duration-200 transform ease-out"
                            x-transition:enter-start="opacity-0 rotate-90 scale-50"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" x-transition:enter="transition duration-200 transform ease-out"
                            x-transition:enter-start="opacity-0 -rotate-90 scale-50"
                            x-transition:enter-end="opacity-100 rotate-0 scale-100" class="w-6 h-6" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    <button
                        class="relative p-2 text-slate-500 hover:text-indigo-500 dark:text-slate-400 transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-slate-700 active:scale-95">
                        <span class="absolute top-1.5 right-1.5 flex h-2.5 w-2.5">
                            <span
                                class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
                        </span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
                            </path>
                        </svg>
                    </button>

                    <div x-data="{ dropdownOpen: false }" class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center space-x-3 focus:outline-none transition-transform active:scale-95 group">
                            <div class="relative">
                                <img class="h-9 w-9 rounded-full object-cover border-2 border-indigo-100 dark:border-indigo-900 group-hover:border-indigo-500 transition-colors shadow-sm"
                                    src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}&background=6366f1&color=fff"
                                    alt="User">
                                <div
                                    class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white dark:border-slate-800 rounded-full">
                                </div>
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 leading-tight">
                                    {{ Auth::user()->name }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Freelancer</p>
                            </div>
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-indigo-500 transition-colors duration-200"
                                :class="dropdownOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        <div x-show="dropdownOpen" @click.away="dropdownOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95 -translate-y-2"
                            x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                            x-transition:leave-end="transform opacity-0 scale-95 -translate-y-2"
                            class="absolute right-0 mt-3 w-56 bg-white dark:bg-slate-800 rounded-xl overflow-hidden shadow-2xl ring-1 ring-black ring-opacity-5 z-50 divide-y divide-slate-100 dark:divide-slate-700"
                            style="display: none;">

                            <div class="px-4 py-3">
                                <p class="text-xs text-slate-500 dark:text-slate-400">Signed in as</p>
                                <p class="text-sm font-medium text-slate-900 dark:text-white truncate">
                                    {{ Auth::user()->email }}</p>
                            </div>

                            <div class="py-1">
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    Profile
                                </a>
                                <a href="#"
                                    class="flex items-center px-4 py-2 text-sm text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                    <svg class="mr-3 h-4 w-4 text-slate-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Settings
                                </a>
                            </div>

                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex w-full items-center px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <svg class="mr-3 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 animate-fade-in-up">
                <div class="container mx-auto max-w-7xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>
</body>

</html>