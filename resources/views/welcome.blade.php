<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>UndanginAja - Platform SaaS Undangan Pernikahan Digital Elegan</title>
    <meta name="description" content="Buat undangan pernikahan digital elegan dan modern dengan UndanginAja. Bagikan via WhatsApp, kelola RSVP tamu secara real-time, dan akses puluhan tema premium.">

    <!-- Open Graph -->
    <meta property="og:title" content="UndanginAja - Platform Undangan Digital Elegan">
    <meta property="og:description" content="Platform SaaS undangan digital pernikahan terlengkap. Bagikan kebahagiaan Anda secara praktis & elegan.">
    <meta property="og:type" content="website">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800|playfair-display:600,700,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .font-serif {
            font-family: 'Playfair Display', Georgia, serif;
        }
        .hero-pattern {
            background-color: #ffffff;
            background-image: radial-gradient(#fecdd3 0.6px, transparent 0.6px), radial-gradient(#fecdd3 0.6px, #ffffff 0.6px);
            background-size: 24px 24px;
            background-position: 0 0, 12px 12px;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-white" x-data="{ mobileNav: false }">

    <!-- Navigation Bar -->
    <nav class="sticky top-0 z-40 bg-white/80 backdrop-blur-md border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <!-- Brand Logo -->
            <a href="/" class="flex items-center gap-3 group">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-rose-500 to-pink-500 flex items-center justify-center text-white shadow-md shadow-pink-500/20 group-hover:scale-105 transition-transform">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M3 7.5l9 6 9-6M3 7.5A1.5 1.5 0 014.5 6h15A1.5 1.5 0 0121 7.5" />
                    </svg>
                </div>
                <div>
                    <span class="text-xl font-extrabold bg-gradient-to-r from-rose-600 to-pink-600 bg-clip-text text-transparent">UndanginAja</span>
                    
                </div>
            </a>

            <!-- Desktop Nav Links -->
            <div class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-600">
                <a href="#fitur" class="hover:text-rose-600 transition">Fitur</a>
                <a href="#templates" class="hover:text-rose-600 transition">Template</a>
                <a href="#harga" class="hover:text-rose-600 transition">Paket Harga</a>
                <a href="#faq" class="hover:text-rose-600 transition">FAQ</a>
            </div>

            <!-- Auth Buttons -->
            <div class="hidden md:flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a 
                            href="{{ route('dashboard') }}" 
                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-xs rounded-xl shadow-md shadow-pink-500/20 hover:from-rose-600 hover:to-pink-600 transition hover:scale-105 active:scale-95"
                        >
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            <span>Buka Dashboard</span>
                        </a>
                    @else
                        <a 
                            href="{{ route('login') }}" 
                            class="px-4 py-2 text-xs font-bold text-slate-700 hover:text-rose-600 transition"
                        >
                            Masuk
                        </a>
                        @if (Route::has('register'))
                            <a 
                                href="{{ route('register') }}" 
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-xs rounded-xl shadow-md shadow-pink-500/20 hover:from-rose-600 hover:to-pink-600 transition hover:scale-105 active:scale-95"
                            >
                                <span>Daftar Gratis</span>
                            </a>
                        @endif
                    @endauth
                @endif
            </div>

            <!-- Mobile Hamburger -->
            <button 
                @click="mobileNav = !mobileNav" 
                type="button" 
                class="md:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg"
            >
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                </svg>
            </button>
        </div>

        <!-- Mobile Nav Menu -->
        <div x-show="mobileNav" x-cloak class="md:hidden border-t border-slate-100 bg-white p-4 space-y-3">
            <a href="#fitur" @click="mobileNav = false" class="block py-2 text-sm font-semibold text-slate-700">Fitur</a>
            <a href="#templates" @click="mobileNav = false" class="block py-2 text-sm font-semibold text-slate-700">Template</a>
            <a href="#harga" @click="mobileNav = false" class="block py-2 text-sm font-semibold text-slate-700">Paket Harga</a>
            <a href="#faq" @click="mobileNav = false" class="block py-2 text-sm font-semibold text-slate-700">FAQ</a>
            <div class="pt-3 border-t border-slate-100 flex flex-col gap-2">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full py-2.5 text-center bg-rose-600 text-white font-bold text-xs rounded-xl">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="w-full py-2.5 text-center bg-slate-100 text-slate-800 font-bold text-xs rounded-xl">Masuk</a>
                    <a href="{{ route('register') }}" class="w-full py-2.5 text-center bg-rose-600 text-white font-bold text-xs rounded-xl">Daftar Gratis</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-pattern pt-16 pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden">
        <div class="max-w-5xl mx-auto text-center space-y-8">
            

            <!-- Headline -->
            <h1 class="text-4xl sm:text-6xl md:text-7xl font-serif font-black text-slate-900 tracking-tight leading-[1.1]">
                Ciptakan Undangan Digital <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-rose-600 via-pink-600 to-rose-500 bg-clip-text text-transparent">Elegan, Praktis & Modern</span>
            </h1>

            <!-- Subtitle -->
            <p class="max-w-2xl mx-auto text-base sm:text-lg text-slate-600 leading-relaxed">
                Bagikan kebahagiaan momen sakral Anda ke ribuan tamu undangan via WhatsApp hanya dengan satu link unik. Nikmati konfirmasi RSVP real-time, integrasi peta venue, dan puluhan tema pilihan.
            </p>

            <!-- CTA Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-2">
                @auth
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-2xl shadow-xl shadow-pink-500/25 hover:from-rose-600 hover:to-pink-600 transition hover:scale-105 active:scale-95"
                    >
                        Buka Dashboard Saya &rarr;
                    </a>
                @else
                    <a 
                        href="{{ route('register') }}" 
                        class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-2xl shadow-xl shadow-pink-500/25 hover:from-rose-600 hover:to-pink-600 transition hover:scale-105 active:scale-95"
                    >
                        Mulai Buat Undangan &bull; Gratis
                    </a>
                    <a 
                        href="#templates" 
                        class="w-full sm:w-auto px-7 py-4 bg-white border border-slate-200 text-slate-700 font-bold text-sm rounded-2xl shadow-sm hover:bg-slate-50 transition"
                    >
                        Lihat Pilihan Template
                    </a>
                @endauth
            </div>

            <!-- Interactive Visual Preview Mockup -->
            <div class="pt-10 max-w-3xl mx-auto">
                <div class="relative bg-white rounded-3xl p-3 sm:p-5 shadow-2xl border border-slate-200/80">
                    <div class="rounded-2xl border border-slate-100 overflow-hidden bg-gradient-to-b from-rose-50/60 via-white to-rose-50/30 p-8 text-center space-y-6">
                        <span class="inline-block px-3 py-1 bg-white rounded-full text-rose-600 text-[11px] font-bold tracking-widest uppercase shadow-sm">
                            The Wedding of
                        </span>
                        <div class="space-y-1">
                            <h3 class="text-3xl sm:text-4xl font-serif font-black text-slate-900">Ahmad Fauzi</h3>
                            <p class="text-xl font-serif text-rose-500 italic">&</p>
                            <h3 class="text-3xl sm:text-4xl font-serif font-black text-slate-900">Siti Nurhaliza</h3>
                        </div>
                        <p class="text-xs sm:text-sm text-slate-500 font-medium">Sabtu, 28 November &bull; Balai Kartini, Jakarta Selatan</p>
                        <div class="pt-2 flex items-center justify-center gap-3">
                            <span class="px-4 py-2 bg-emerald-100 text-emerald-800 text-xs font-bold rounded-xl flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                                <span>RSVP Real-Time Aktif</span>
                            </span>
                            <span class="px-4 py-2 bg-rose-100 text-rose-800 text-xs font-bold rounded-xl flex items-center gap-1.5">
                                <span>Google Maps Ready</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Social Proof Stats -->
    <section class="py-12 bg-slate-900 text-white border-y border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold text-rose-400">10.000+</p>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Undangan Dibuat</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold text-rose-400">50.000+</p>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Tamu Terkonfirmasi</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold text-rose-400">99.9%</p>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Uptime Server Cepat</p>
                </div>
                <div>
                    <p class="text-3xl sm:text-4xl font-extrabold text-rose-400">4.9 / 5</p>
                    <p class="text-xs sm:text-sm text-slate-400 mt-1 font-medium">Kepuasan Pengantin</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50/60">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-widest">Fitur Lengkap SaaS</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-black text-slate-900 mt-2">Segala Kebutuhan Undangan dalam Satu Platform</h2>
                <p class="text-sm text-slate-600 mt-3">Nikmati kemudahan pembuatan dan pengelolaan resepsi tanpa ribet mencetak kertas.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Konfirmasi RSVP Real-Time</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Tamu dapat langsung konfirmasi hadir, tidak hadir, atau masih ragu beserta ucapan doa restu yang tersimpan otomatis di dashboard Anda.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Navigasi Peta Google Maps</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Cukup cantumkan link venue, tamu dapat langsung membuka rute Google Maps tanpa takut tersesat menuju lokasi pesta pernikahan.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Puluhan Pilihan Desain</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Pilih dari berbagai kategori tema seperti Elegant, Rustic, Modern, dan Minimalist yang dirancang oleh desainer profesional.
                    </p>
                </div>

                <!-- Feature 4 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Ekspor Tamu ke CSV</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Unduh daftar kehadiran tamu ke format spreadsheet CSV kapan saja untuk koordinasi katering, souvenir, dan nomor meja resepsi.
                    </p>
                </div>

                <!-- Feature 5 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">Midtrans Payment Gateway</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Upgrade paket Pro atau Business dengan pembayaran instan otomatis melalui QRIS, GoPay, OVO, ShopeePay, dan Transfer Bank.
                    </p>
                </div>

                <!-- Feature 6 -->
                <div class="bg-white p-8 rounded-3xl shadow-sm border border-slate-100 space-y-4 hover:shadow-md transition">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                        </svg>
                    </div>
                    <h3 class="font-bold text-slate-900 text-lg">100% Mobile Responsive</h3>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Dibangun dengan teknologi modern yang sangat ringan, cepat terbuka, dan ramah diakses dari berbagai layar smartphone tamu undangan.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Templates Showcase Section -->
    <section id="templates" class="py-24 px-4 sm:px-6 lg:px-8 bg-white">
        <div class="max-w-7xl mx-auto space-y-16">
            <div class="text-center max-w-2xl mx-auto">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-widest">Koleksi Desain</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-black text-slate-900 mt-2">Pilihan Tema Template Eksklusif</h2>
                <p class="text-sm text-slate-600 mt-3">Sesuaikan tema undangan pernikahan Anda dengan konsep dan warna dekorasi pesta.</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($templates as $template)
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden flex flex-col group hover:shadow-md transition">
                        <div class="aspect-[3/4] bg-rose-50 relative overflow-hidden">
                            @if($template->thumbnail)
                                <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-rose-300">
                                    <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke-width="1.2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="absolute top-3 left-3">
                                @php
                                    $catVal = is_object($template->category) ? $template->category->value : (string) $template->category;
                                @endphp
                                <span class="px-2.5 py-1 bg-white/90 backdrop-blur-sm text-[10px] font-bold text-slate-800 rounded-full shadow-sm capitalize">
                                    {{ $catVal }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex items-center justify-between">
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm truncate">{{ $template->name }}</h4>
                            @if($template->is_premium)
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-amber-100 text-amber-700">Premium</span>
                            @else
                                <span class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-700">Gratis</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="harga" class="py-24 px-4 sm:px-6 lg:px-8 bg-slate-50/70 border-t border-slate-100">
        <div class="max-w-5xl mx-auto space-y-16">
            <div class="text-center max-w-xl mx-auto">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-widest">Biaya & Langganan</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-black text-slate-900 mt-2">Pilihan Paket yang Terjangkau</h2>
                <p class="text-sm text-slate-600 mt-3">Mulai secara gratis sekarang atau upgrade kapan saja saat membutuhkan fitur eksklusif.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-6 items-stretch">
                @foreach($plans as $plan)
                    @php
                        $isPopular = $plan->is_popular;
                    @endphp

                    <div class="relative flex flex-col {{ $isPopular ? 'md:-translate-y-3' : '' }}">
                        @if($isPopular)
                            <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 z-10">
                                <span class="px-3.5 py-1 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[11px] font-bold rounded-full shadow-md uppercase tracking-wider">
                                    Paling Populer
                                </span>
                            </div>
                        @endif

                        <div class="h-full bg-white rounded-3xl shadow-sm border-2 {{ $isPopular ? 'border-rose-500 shadow-xl' : 'border-slate-100' }} p-6 sm:p-8 flex flex-col justify-between">
                            <div>
                                <div class="pb-6 border-b border-slate-100">
                                    <h3 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h3>
                                    <div class="mt-4 flex items-baseline">
                                        @if($plan->price === 0)
                                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">Gratis</span>
                                        @else
                                            <span class="text-3xl sm:text-4xl font-extrabold text-slate-900">{{ $plan->formatted_price }}</span>
                                            <span class="text-xs text-slate-400 font-medium ml-1">/ event</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-slate-500 mt-2">Maksimal {{ $plan->max_invitations == 999 ? 'Unlimited' : $plan->max_invitations }} undangan digital.</p>
                                </div>

                                <div class="py-6">
                                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3.5">Fitur:</p>
                                    <ul class="space-y-3">
                                        @if(is_array($plan->features))
                                            @foreach($plan->features as $feature)
                                                <li class="flex items-start gap-2.5 text-xs text-slate-600">
                                                    <svg class="w-4 h-4 text-emerald-500 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                    </svg>
                                                    <span>{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                            </div>

                            <div class="pt-4">
                                @auth
                                    <a
                                        href="{{ route('subscription.index') }}"
                                        class="block w-full py-3 text-center text-xs font-bold rounded-xl text-white transition shadow-sm {{ $isPopular ? 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600' : 'bg-slate-900 hover:bg-slate-800' }}"
                                    >
                                        Pilih Paket {{ $plan->name }}
                                    </a>
                                @else
                                    <a
                                        href="{{ route('register') }}"
                                        class="block w-full py-3 text-center text-xs font-bold rounded-xl text-white transition shadow-sm {{ $isPopular ? 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600' : 'bg-slate-900 hover:bg-slate-800' }}"
                                    >
                                        Daftar & Pilih Paket
                                    </a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- FAQ Accordion -->
    <section id="faq" class="py-24 px-4 sm:px-6 lg:px-8 bg-white border-t border-slate-100">
        <div class="max-w-3xl mx-auto space-y-12">
            <div class="text-center">
                <span class="text-xs font-bold text-rose-600 uppercase tracking-widest">Bantuan & FAQ</span>
                <h2 class="text-3xl font-serif font-black text-slate-900 mt-2">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-4" x-data="{ activeAccordion: null }">
                <!-- FAQ 1 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer" @click="activeAccordion = activeAccordion === 1 ? null : 1">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-sm">Apakah saya bisa membuat undangan secara gratis?</h4>
                        <span class="text-slate-400 font-bold" x-text="activeAccordion === 1 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeAccordion === 1" x-cloak class="mt-3 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Ya, Anda dapat mendaftar dan membuat 1 undangan secara gratis dengan fitur dasar RSVP dan pilihan template gratis.
                    </div>
                </div>

                <!-- FAQ 2 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer" @click="activeAccordion = activeAccordion === 2 ? null : 2">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-sm">Bagaimana cara menyebarkan undangan ke tamu?</h4>
                        <span class="text-slate-400 font-bold" x-text="activeAccordion === 2 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeAccordion === 2" x-cloak class="mt-3 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Setelah undangan dipublikasikan, Anda akan mendapatkan tautan unik (contoh: <code>/undangan/ahmad-dan-siti</code>) yang dapat disalin dan dibagikan langsung ke WhatsApp dengan satu tombol.
                    </div>
                </div>

                <!-- FAQ 3 -->
                <div class="border border-slate-200 rounded-2xl p-5 cursor-pointer" @click="activeAccordion = activeAccordion === 3 ? null : 3">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-slate-900 text-sm">Bagaimana proses pembayaran jika ingin upgrade ke Pro?</h4>
                        <span class="text-slate-400 font-bold" x-text="activeAccordion === 3 ? '−' : '+'"></span>
                    </div>
                    <div x-show="activeAccordion === 3" x-cloak class="mt-3 text-xs text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                        Pembayaran diproses otomatis oleh Midtrans melalui popup pembayaran langsung dengan metode QRIS (GoPay, OVO, ShopeePay), Transfer Bank VA, maupun Kartu Kredit.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Banner -->
    <section class="py-16 px-4 bg-gradient-to-r from-rose-600 via-pink-600 to-rose-500 text-white text-center">
        <div class="max-w-2xl mx-auto space-y-6">
            <h2 class="text-3xl sm:text-4xl font-serif font-black tracking-tight">Siap Membuat Momen Bahagiamu Berkesan?</h2>
            <p class="text-sm text-white/90">Bergabunglah dengan ribuan pasangan yang telah mempercayakan undangan pernikahan mereka pada UndanginAja.</p>
            @auth
                <a href="{{ route('dashboard') }}" class="inline-block px-8 py-4 bg-white text-rose-600 font-bold text-sm rounded-2xl shadow-lg hover:bg-rose-50 transition">
                    Buka Dashboard Sekarang
                </a>
            @else
                <a href="{{ route('register') }}" class="inline-block px-8 py-4 bg-white text-rose-600 font-bold text-sm rounded-2xl shadow-lg hover:bg-rose-50 transition">
                    Daftar Sekarang &bull; 100% Gratis
                </a>
            @endauth
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 px-4 bg-slate-950 text-slate-400 border-t border-slate-900 text-xs text-center space-y-4">
        <div class="flex items-center justify-center gap-2">
            <div class="w-6 h-6 rounded-lg bg-rose-500 flex items-center justify-center text-white">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H4.5a1.5 1.5 0 01-1.5-1.5v-8.25M3 7.5l9 6 9-6M3 7.5A1.5 1.5 0 014.5 6h15A1.5 1.5 0 0121 7.5" />
                </svg>
            </div>
            <span class="text-sm font-bold text-white">UndanginAja</span>
        </div>
        <p>&copy; {{ date('Y') }} UndanginAja. Seluruh hak cipta dilindungi undang-undang.</p>
    </footer>

</body>
</html>
