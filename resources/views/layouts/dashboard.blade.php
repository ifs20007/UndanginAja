<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'UndanginAja') }} - Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-50" x-data="{ sidebarOpen: false, profileDropdownOpen: false }">
    <div class="min-h-screen flex flex-col lg:flex-row">

        <!-- Mobile Backdrop Overlay -->
        <div 
            x-show="sidebarOpen" 
            x-cloak
            x-transition:enter="transition-opacity ease-linear duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-linear duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            @click="sidebarOpen = false" 
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-sm lg:hidden"
            aria-hidden="true"
        ></div>

        <!-- Mobile Sidebar Off-canvas -->
        <aside 
            x-show="sidebarOpen"
            x-cloak
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="fixed inset-y-0 left-0 z-50 w-72 bg-white shadow-2xl flex flex-col lg:hidden"
        >
            <!-- Mobile Header inside sidebar -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-pink-500/20">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M3 7.5l9 6 9-6M3 7.5A1.5 1.5 0 014.5 6h15A1.5 1.5 0 0121 7.5" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">UndanginAja</span>
                        <span class="block text-xs text-slate-400 font-medium">Dashboard</span>
                    </div>
                </a>
                <button 
                    @click="sidebarOpen = false" 
                    type="button" 
                    class="p-2 -mr-2 text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 transition-colors"
                >
                    <span class="sr-only">Tutup menu</span>
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Navigation Links -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1.5">
                @php
                    $isDashboard = request()->routeIs('dashboard*');
                    $isInvitations = request()->routeIs('invitations*');
                    $isTemplates = request()->routeIs('templates*');
                    $isSubscriptions = request()->routeIs('plans*', 'subscription*');
                @endphp

                <!-- Dashboard Link -->
                <a 
                    href="{{ route('dashboard') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isDashboard ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 {{ $isDashboard ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- Undangan Saya Link -->
                <a 
                    href="{{ Route::has('invitations.index') ? route('invitations.index') : (Route::has('invitations') ? route('invitations') : '#') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isInvitations ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 {{ $isInvitations ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span>Undangan Saya</span>
                </a>

                <!-- Template Link -->
                <a 
                    href="{{ Route::has('templates.index') ? route('templates.index') : (Route::has('templates') ? route('templates') : '#') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isTemplates ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 {{ $isTemplates ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.14 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                    </svg>
                    <span>Template</span>
                </a>

                <!-- Langganan Link -->
                <a 
                    href="{{ Route::has('plans.index') ? route('plans.index') : (Route::has('subscriptions.index') ? route('subscriptions.index') : '#') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isSubscriptions ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 {{ $isSubscriptions ? 'text-rose-600' : 'text-slate-400' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    <span>Langganan</span>
                </a>
            </div>

            <!-- Mobile Footer Profile -->
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 font-bold flex items-center justify-center text-sm ring-2 ring-white">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'User', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()?->name ?? 'Guest User' }}</p>
                        <p class="text-xs text-slate-500 truncate">{{ auth()->user()?->email ?? 'guest@undanginaja.com' }}</p>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Desktop Fixed Sidebar -->
        <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:fixed lg:inset-y-0 lg:left-0 lg:z-30 bg-white border-r border-slate-200/80">
            <!-- Sidebar Header / Logo -->
            <div class="h-16 flex items-center px-6 border-b border-slate-100">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-pink-500/20 group-hover:scale-105 transition-transform">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M3 7.5l9 6 9-6M3 7.5A1.5 1.5 0 014.5 6h15A1.5 1.5 0 0121 7.5" />
                        </svg>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">UndanginAja</span>
                        <span class="block text-xs text-slate-400 font-medium">Invitation Platform</span>
                    </div>
                </a>
            </div>

            <!-- Subscription Badge Card in Sidebar -->
            <div class="p-4 mx-4 my-3 rounded-xl bg-gradient-to-br from-rose-50 to-pink-50 border border-rose-100/60">
                <div class="flex items-center justify-between mb-1.5">
                    <span class="text-xs font-semibold text-rose-700 uppercase tracking-wider">Paket Akun</span>
                    <span class="px-2 py-0.5 text-[11px] font-bold rounded-full {{ auth()->user()?->subscription_plan ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-700' }}">
                        {{ auth()->user()?->subscription_plan ? ucfirst(auth()->user()->subscription_plan) : 'Free' }}
                    </span>
                </div>
                <p class="text-xs text-slate-600">
                    {{ auth()->user()?->subscription_expires_at ? 'Berlaku s/d ' . auth()->user()->subscription_expires_at->format('d M Y') : 'Upgrade untuk fitur premium' }}
                </p>
            </div>

            <!-- Desktop Navigation Menu -->
            <nav class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                @php
                    $isDashboard = request()->routeIs('dashboard*');
                    $isInvitations = request()->routeIs('invitations*');
                    $isTemplates = request()->routeIs('templates*');
                    $isSubscriptions = request()->routeIs('plans*', 'subscription*');
                @endphp

                <!-- 1. Dashboard -->
                <a 
                    href="{{ route('dashboard') }}" 
                    class="group flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isDashboard ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 transition-colors {{ $isDashboard ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25a2.25 2.25 0 01-13.5 18v-2.25z" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                <!-- 2. Undangan Saya -->
                <a 
                    href="{{ Route::has('invitations.index') ? route('invitations.index') : (Route::has('invitations') ? route('invitations') : '#') }}" 
                    class="group flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isInvitations ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 transition-colors {{ $isInvitations ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span>Undangan Saya</span>
                </a>

                <!-- 3. Template -->
                <a 
                    href="{{ Route::has('templates.index') ? route('templates.index') : (Route::has('templates') ? route('templates') : '#') }}" 
                    class="group flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isTemplates ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 transition-colors {{ $isTemplates ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.53 16.122a3 3 0 00-5.78 1.128 2.25 2.25 0 01-2.4 2.245 4.5 4.5 0 008.4-2.245c0-.399-.078-.78-.22-1.128zm0 0a15.998 15.998 0 003.388-1.62m-5.043-.025a15.994 15.994 0 011.622-3.395m3.42 3.42a15.995 15.995 0 004.764-4.648l3.876-5.814a1.151 1.151 0 00-1.597-1.597L14.14 6.32a15.996 15.996 0 00-4.649 4.763m3.42 3.42a6.776 6.776 0 00-3.42-3.42" />
                    </svg>
                    <span>Template</span>
                </a>

                <!-- 4. Langganan -->
                <a 
                    href="{{ Route::has('plans.index') ? route('plans.index') : (Route::has('subscriptions.index') ? route('subscriptions.index') : '#') }}" 
                    class="group flex items-center gap-3.5 px-3.5 py-2.5 rounded-xl text-sm font-semibold transition-all {{ $isSubscriptions ? 'bg-rose-50 text-rose-600 shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}"
                >
                    <svg class="w-5 h-5 transition-colors {{ $isSubscriptions ? 'text-rose-600' : 'text-slate-400 group-hover:text-slate-600' }}" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                    </svg>
                    <span>Langganan</span>
                </a>
            </nav>

            <!-- Desktop Sidebar Footer -->
            <div class="p-4 border-t border-slate-100">
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 p-2 rounded-xl hover:bg-slate-50 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-600 font-bold flex items-center justify-center text-sm ring-2 ring-rose-200/50 group-hover:scale-105 transition-transform">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'User', 0, 2)) }}
                    </div>
                    <div class="flex-1 min-w-0 text-left">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()?->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-slate-400 truncate">Kelola Profil</p>
                    </div>
                    <svg class="w-4 h-4 text-slate-400 group-hover:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            </div>
        </aside>

        <!-- Main Content Wrapper (offset for fixed sidebar on lg screens) -->
        <div class="lg:pl-64 flex flex-col flex-1 min-w-0">
            
            <!-- Sticky Header / Topbar -->
            <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-slate-200/80 px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">
                
                <!-- Left: Mobile Menu Toggle & Title -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <button 
                        @click="sidebarOpen = true" 
                        type="button" 
                        class="p-2 -ml-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 lg:hidden focus:outline-none focus:ring-2 focus:ring-rose-500"
                    >
                        <span class="sr-only">Buka menu sidebar</span>
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>

                    <div>
                        @isset($header)
                            {{ $header }}
                        @else
                            <h1 class="text-lg sm:text-xl font-bold text-slate-800">
                                @yield('title', 'Dashboard')
                            </h1>
                        @endisset
                    </div>
                </div>

                <!-- Right: Quick Actions & Profile Dropdown -->
                <div class="flex items-center gap-2.5 sm:gap-3">
                    @hasSection('actions')
                        @yield('actions')
                    @else
                        <!-- Quick CTA: Buat Undangan -->
                        <a 
                            href="{{ Route::has('invitations.create') ? route('invitations.create') : '#' }}" 
                            class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 rounded-xl text-xs sm:text-sm font-semibold text-white bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 shadow-sm shadow-pink-500/25 transition-all hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                            <span>Buat Undangan</span>
                        </a>
                    @endif

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button 
                            @click="open = !open" 
                            type="button" 
                            class="flex items-center gap-2 p-1.5 rounded-xl hover:bg-slate-100 text-slate-700 transition-colors focus:outline-none focus:ring-2 focus:ring-rose-500"
                            aria-expanded="false"
                        >
                            <div class="w-8 h-8 rounded-full bg-rose-100 text-rose-600 font-bold flex items-center justify-center text-xs ring-1 ring-slate-200">
                                {{ strtoupper(substr(auth()->user()?->name ?? 'User', 0, 2)) }}
                            </div>
                            <span class="hidden md:inline-block text-xs font-semibold text-slate-700 max-w-[120px] truncate">
                                {{ auth()->user()?->name ?? 'User' }}
                            </span>
                            <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open" 
                            x-cloak
                            @click.outside="open = false" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-xl border border-slate-100 py-1.5 z-50 divide-y divide-slate-100"
                        >
                            <div class="px-4 py-2.5">
                                <p class="text-xs text-slate-400 font-medium">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ auth()->user()?->name ?? 'User' }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ auth()->user()?->email ?? 'user@example.com' }}</p>
                            </div>

                            <div class="py-1">
                                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    <span>Profil Saya</span>
                                </a>

                                <a href="{{ Route::has('plans.index') ? route('plans.index') : (Route::has('subscriptions.index') ? route('subscriptions.index') : '#') }}" class="flex items-center gap-2.5 px-4 py-2 text-xs sm:text-sm font-medium text-slate-700 hover:bg-slate-50 transition-colors">
                                    <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09z" />
                                    </svg>
                                    <span>Langganan & Paket</span>
                                </a>
                            </div>

                            <div class="py-1">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="w-full flex items-center gap-2.5 px-4 py-2 text-xs sm:text-sm font-medium text-rose-600 hover:bg-rose-50 transition-colors text-left"
                                    >
                                        <svg class="w-4 h-4 text-rose-500" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                        </svg>
                                        <span>Keluar</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">
                <!-- Flash Notification Banner -->
                @if (session('status'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-6 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-3">
                        <svg class="w-5 h-5 text-rose-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Dynamic Content Slot / Section -->
                @isset($slot)
                    {{ $slot }}
                @else
                    @yield('content')
                @endisset
            </main>

            <!-- Dashboard Footer -->
            <footer class="py-6 px-4 sm:px-6 lg:px-8 border-t border-slate-200/60 text-center text-xs text-slate-400">
                <p>&copy; {{ date('Y') }} UndanginAja &bull; Platform Undangan Digital Elegan</p>
            </footer>
        </div>
    </div>

    @stack('scripts')
</body>
</html>
