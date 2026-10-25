<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $invitation->groom_name }} & {{ $invitation->bride_name }} - Undangan Pernikahan</title>
    <meta name="description" content="Kami mengundang Anda untuk hadir dan memberikan doa restu di pernikahan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}">

    <!-- Open Graph for WhatsApp, Instagram, Facebook -->
    <meta property="og:title" content="Undangan Pernikahan {{ $invitation->groom_name }} & {{ $invitation->bride_name }}">
    <meta property="og:description" content="Pernikahan pada {{ $invitation->event_date?->format('d F Y') }} di {{ $invitation->venue_name }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,600,700,900|figtree:400,500,600|cinzel:400,600,700|great-vibes:400|montserrat:400,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $cfg = $invitation->theme_config ?? [];
        $bankAcc = $cfg['bank_accounts'][0] ?? [];
        $bankName = $bankAcc['bank_name'] ?? ($cfg['bank_name'] ?? '');
        $accountNumber = $bankAcc['account_number'] ?? ($cfg['bank_account'] ?? '');
        $accountHolder = $bankAcc['account_holder'] ?? ($cfg['bank_owner'] ?? $invitation->groom_name);
        $targetFont = $invitation->custom_font ?: 'Playfair Display';
        $bgMusic = $invitation->music_url ?: ($cfg['audio_url'] ?? '');
        $dresscode = $invitation->dresscode ?: ($cfg['dress_code_desc'] ?? '');
    @endphp

    <style>
        .font-serif {
            font-family: '{{ $targetFont }}', Georgia, serif;
        }
        .romantic-pattern {
            background-color: #fffafb;
            background-image: radial-gradient(#fecdd3 0.75px, transparent 0.75px), radial-gradient(#fecdd3 0.75px, #fffafb 0.75px);
            background-size: 30px 30px;
            background-position: 0 0, 15px 15px;
        }
        @keyframes spin-slow {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        .animate-spin-slow {
            animation: spin-slow 8s linear infinite;
        }
    </style>
</head>
<body class="font-sans antialiased text-slate-800 bg-slate-50 relative selection:bg-rose-500 selection:text-white" x-data="invitationApp()">

    <!-- Floating Background Music Player -->
    @if(!empty($bgMusic))
        <div class="fixed bottom-6 right-6 z-40">
            <button
                type="button"
                @click="toggleMusic"
                class="w-12 h-12 rounded-full bg-white/90 backdrop-blur border border-rose-200 shadow-xl text-rose-600 flex items-center justify-center transition hover:scale-110 active:scale-95 group focus:outline-none focus:ring-2 focus:ring-rose-400"
                :title="isPlaying ? 'Hentikan Musik' : 'Putar Musik'"
            >
                <div :class="isPlaying ? 'animate-spin-slow' : ''" class="flex items-center justify-center">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-6z"/>
                    </svg>
                </div>
                <!-- Play status badge -->
                <span 
                    class="absolute -top-1 -right-1 w-3.5 h-3.5 rounded-full border-2 border-white transition-colors"
                    :class="isPlaying ? 'bg-emerald-500 animate-pulse' : 'bg-slate-400'"
                ></span>
            </button>
        </div>
    @endif

    <!-- 1. Hero Section -->
    <section class="min-h-screen romantic-pattern flex items-center justify-center px-4 py-20 relative overflow-hidden">
        <div class="text-center max-w-2xl mx-auto space-y-6">
            <div class="inline-block px-5 py-1.5 rounded-full bg-rose-100/90 text-rose-700 text-xs font-bold tracking-[0.25em] uppercase shadow-sm">
                The Wedding of
            </div>

            <div class="space-y-3 pt-2">
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-serif font-black text-slate-900 tracking-tight leading-tight">
                    {{ $invitation->groom_name }}
                </h1>
                <p class="text-2xl sm:text-4xl font-serif text-rose-500 italic">&</p>
                <h1 class="text-4xl sm:text-6xl md:text-7xl font-serif font-black text-slate-900 tracking-tight leading-tight">
                    {{ $invitation->bride_name }}
                </h1>
            </div>

            <div class="pt-4 space-y-1">
                <p class="text-base sm:text-lg font-bold text-slate-700">
                    {{ $invitation->event_date?->translatedFormat('l, d F Y') }}
                </p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">
                    {{ $invitation->venue_name }} &bull; {{ $invitation->event_time?->format('H:i') ?? '09:00' }} WIB
                </p>
            </div>

            <!-- Countdown Timer Widget -->
            <div class="pt-6 pb-2" x-data="countdownTimer('{{ $invitation->event_date?->format('Y-m-d') }} {{ $invitation->event_time?->format('H:i:s') ?? '09:00:00' }}')">
                <div class="grid grid-cols-4 gap-2 sm:gap-4 max-w-sm mx-auto">
                    <div class="p-3 bg-white/90 backdrop-blur rounded-2xl border border-rose-100 shadow-sm">
                        <span class="block text-xl sm:text-2xl font-black text-rose-600 font-serif" x-text="days">0</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Hari</span>
                    </div>
                    <div class="p-3 bg-white/90 backdrop-blur rounded-2xl border border-rose-100 shadow-sm">
                        <span class="block text-xl sm:text-2xl font-black text-rose-600 font-serif" x-text="hours">0</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Jam</span>
                    </div>
                    <div class="p-3 bg-white/90 backdrop-blur rounded-2xl border border-rose-100 shadow-sm">
                        <span class="block text-xl sm:text-2xl font-black text-rose-600 font-serif" x-text="minutes">0</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Menit</span>
                    </div>
                    <div class="p-3 bg-white/90 backdrop-blur rounded-2xl border border-rose-100 shadow-sm">
                        <span class="block text-xl sm:text-2xl font-black text-rose-600 font-serif" x-text="seconds">0</span>
                        <span class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Detik</span>
                    </div>
                </div>
            </div>

            <div class="pt-6">
                <a 
                    href="#mempelai" 
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-full shadow-lg shadow-pink-500/25 hover:from-rose-600 hover:to-pink-600 transition hover:scale-105 active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span>Buka Undangan</span>
                </a>
            </div>
        </div>
    </section>

    <!-- 2. Section Kedua Mempelai & Orang Tua -->
    <section id="mempelai" class="py-24 px-4 bg-white border-t border-slate-100">
        <div class="max-w-3xl mx-auto text-center space-y-12">
            <div class="space-y-3">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Kedua Mempelai</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Mempelai Pria &amp; Wanita</h2>
                <p class="text-xs sm:text-sm text-slate-500 max-w-lg mx-auto italic leading-relaxed">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                    <span class="block font-semibold not-italic text-slate-700 mt-1">(QS. Ar-Rum: 21)</span>
                </p>
            </div>

            <div class="grid sm:grid-cols-2 gap-8 items-center pt-4">
                <!-- Mempelai Pria -->
                <div class="p-8 bg-rose-50/40 rounded-3xl border border-rose-100 space-y-4 shadow-sm hover:shadow-md transition">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-rose-500 to-pink-400 text-white flex items-center justify-center text-2xl font-serif font-bold shadow-md shadow-rose-300">
                        {{ substr($invitation->groom_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-serif font-bold text-slate-900">{{ $invitation->groom_name }}</h3>
                        @if(!empty($cfg['groom_nickname']))
                            <p class="text-xs font-bold text-rose-600 mt-0.5">({{ $cfg['groom_nickname'] }})</p>
                        @endif
                    </div>
                    <div class="text-xs text-slate-600 space-y-1 pt-2 border-t border-rose-100/60">
                        <p class="font-medium text-slate-500">{{ $cfg['groom_order'] ?? 'Putra pertama dari' }}</p>
                        <p class="font-bold text-slate-800">
                            Bpk. {{ !empty($cfg['groom_father']) ? $cfg['groom_father'] : 'Orang Tua Pria' }} &amp; Ibu {{ !empty($cfg['groom_mother']) ? $cfg['groom_mother'] : 'Orang Tua Pria' }}
                        </p>
                    </div>
                </div>

                <!-- Mempelai Wanita -->
                <div class="p-8 bg-rose-50/40 rounded-3xl border border-rose-100 space-y-4 shadow-sm hover:shadow-md transition">
                    <div class="w-20 h-20 mx-auto rounded-full bg-gradient-to-tr from-pink-400 to-rose-500 text-white flex items-center justify-center text-2xl font-serif font-bold shadow-md shadow-rose-300">
                        {{ substr($invitation->bride_name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl sm:text-2xl font-serif font-bold text-slate-900">{{ $invitation->bride_name }}</h3>
                        @if(!empty($cfg['bride_nickname']))
                            <p class="text-xs font-bold text-rose-600 mt-0.5">({{ $cfg['bride_nickname'] }})</p>
                        @endif
                    </div>
                    <div class="text-xs text-slate-600 space-y-1 pt-2 border-t border-rose-100/60">
                        <p class="font-medium text-slate-500">{{ $cfg['bride_order'] ?? 'Putri kedua dari' }}</p>
                        <p class="font-bold text-slate-800">
                            Bpk. {{ !empty($cfg['bride_father']) ? $cfg['bride_father'] : 'Orang Tua Wanita' }} &amp; Ibu {{ !empty($cfg['bride_mother']) ? $cfg['bride_mother'] : 'Orang Tua Wanita' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- 3. Section Detail Acara (Akad Nikah & Resepsi) -->
    <section class="py-24 px-4 bg-slate-50 border-t border-slate-100">
        <div class="max-w-3xl mx-auto text-center space-y-12">
            <div class="space-y-2">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Agenda Acara</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Waktu &amp; Tempat Pelaksanaan</h2>
            </div>

            <div class="grid sm:grid-cols-2 gap-6 text-left">
                <!-- Sesi Akad Nikah -->
                <div class="p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-4 hover:border-rose-300 transition">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-50 text-rose-700 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        Akad Nikah
                    </div>
                    <div class="space-y-1">
                        <p class="text-base font-bold text-slate-900 font-serif">
                            {{ $invitation->event_date?->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-xs font-semibold text-rose-600">
                            Pukul {{ $cfg['akad_time'] ?? ($invitation->event_time?->format('H:i') . ' WIB - Selesai') }}
                        </p>
                    </div>
                    <div class="text-xs text-slate-600 pt-2 border-t border-slate-100 space-y-1">
                        <p class="font-bold text-slate-900">{{ $invitation->venue_name }}</p>
                        <p class="text-slate-500">{{ $invitation->venue_address }}</p>
                    </div>
                </div>

                <!-- Sesi Resepsi -->
                <div class="p-8 bg-white rounded-3xl border border-slate-200/80 shadow-sm space-y-4 hover:border-rose-300 transition">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-pink-50 text-pink-700 text-xs font-bold uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                        Resepsi Pernikahan
                    </div>
                    <div class="space-y-1">
                        <p class="text-base font-bold text-slate-900 font-serif">
                            {{ $invitation->event_date?->translatedFormat('l, d F Y') }}
                        </p>
                        <p class="text-xs font-semibold text-rose-600">
                            Pukul {{ $cfg['resepsi_time'] ?? '11:00 WIB - Selesai' }}
                        </p>
                    </div>
                    <div class="text-xs text-slate-600 pt-2 border-t border-slate-100 space-y-1">
                        <p class="font-bold text-slate-900">{{ $invitation->venue_name }}</p>
                        <p class="text-slate-500">{{ $invitation->venue_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Petunjuk Busana (Dresscode) Notice -->
            @if(!empty($dresscode))
                <div class="p-5 bg-white rounded-2xl border border-rose-100/80 inline-flex items-center gap-3 text-xs text-slate-700 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <div class="text-left">
                        <p class="font-bold text-slate-900">Petunjuk Busana (Dresscode):</p>
                        <p class="text-slate-600">{{ $dresscode }}</p>
                    </div>
                </div>
            @endif

            <!-- Google Maps Link -->
            @if($invitation->google_maps_url)
                <div class="pt-2">
                    <a
                        href="{{ $invitation->google_maps_url }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-7 py-3.5 bg-slate-900 text-white font-bold text-xs rounded-full hover:bg-slate-800 transition shadow-md hover:scale-105 active:scale-95"
                    >
                        <svg class="w-4 h-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                        <span>Buka Petunjuk Arah Google Maps</span>
                    </a>
                </div>
            @endif
        </div>
    </section>

    <!-- 4. Section Live Streaming (if configured) -->
    @if(!empty($invitation->live_streaming_url))
        <section class="py-16 px-4 bg-gradient-to-r from-slate-900 to-rose-950 text-white">
            <div class="max-w-2xl mx-auto text-center space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/20 text-rose-300 text-xs font-bold border border-rose-500/30">
                    <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-ping"></span>
                    Live Streaming Acara
                </div>
                <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white">Saksikan Momen Bahagia Kami Secara Online</h3>
                <p class="text-xs sm:text-sm text-slate-300 max-w-md mx-auto">
                    Bagi keluarga dan sahabat yang belum dapat hadir secara langsung, silakan bergabung bersama kami melalui siaran langsung virtual.
                </p>
                <div>
                    <a
                        href="{{ $invitation->live_streaming_url }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-rose-500 text-white font-bold text-xs rounded-full hover:bg-rose-600 transition shadow-lg shadow-rose-500/30 hover:scale-105"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z" />
                        </svg>
                        <span>Tonton Live Streaming</span>
                    </a>
                </div>
            </div>
        </section>
    @endif

    <!-- 5. Section Kisah Cinta Kami (Love Story Timeline) -->
    @if($invitation->love_stories && count($invitation->love_stories) > 0)
        <section class="py-24 px-4 bg-white border-t border-slate-100">
            <div class="max-w-3xl mx-auto space-y-12">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Our Love Journey</span>
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Kisah Cinta Kami</h2>
                    <p class="text-xs sm:text-sm text-slate-500 max-w-md mx-auto">
                        Setiap kisah cinta memiliki lembaran indah, dan ini adalah kisah perjalanan kami menuju pelaminan.
                    </p>
                </div>

                <div class="relative wrap overflow-hidden p-2 sm:p-6">
                    <!-- Vertical Timeline Line -->
                    <div class="absolute border-opacity-30 border-rose-300 h-full border-2 left-4 sm:left-1/2 -ml-0.5 sm:-ml-px"></div>

                    <div class="space-y-8">
                        @foreach($invitation->love_stories as $index => $story)
                            @php
                                $isEven = $index % 2 === 0;
                                $storyDate = $story['date'] ?? ($story['year'] ?? '');
                                $storyTitle = $story['title'] ?? '';
                                $storyText = $story['story'] ?? '';
                            @endphp
                            @if(!empty($storyTitle) || !empty($storyText))
                                <div class="relative flex flex-col sm:flex-row items-start sm:items-center {{ $isEven ? 'sm:flex-row-reverse' : '' }}">
                                    <!-- Timeline Node -->
                                    <div class="absolute left-4 sm:left-1/2 -ml-3 sm:-ml-4 w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gradient-to-r from-rose-500 to-pink-500 border-4 border-white shadow-md flex items-center justify-center text-white text-[10px] font-bold z-10">
                                        {{ $index + 1 }}
                                    </div>

                                    <!-- Content Card -->
                                    <div class="ml-10 sm:ml-0 w-full sm:w-1/2 {{ $isEven ? 'sm:pl-8' : 'sm:pr-8' }}">
                                        <div class="p-6 bg-rose-50/50 hover:bg-rose-50/80 rounded-3xl border border-rose-100 shadow-sm transition hover:shadow-md">
                                            @if(!empty($storyDate))
                                                <span class="inline-block px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-100 text-rose-700 mb-2">
                                                    {{ $storyDate }}
                                                </span>
                                            @endif
                                            <h3 class="text-base sm:text-lg font-bold text-slate-900 font-serif">{{ $storyTitle }}</h3>
                                            <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">{{ $storyText }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- 6. Section Galeri Foto (dengan Lightbox Modal) -->
    @if($invitation->galleries && $invitation->galleries->count() > 0)
        <section class="py-24 px-4 bg-slate-50 border-t border-slate-100">
            <div class="max-w-4xl mx-auto space-y-10 text-center">
                <div class="space-y-2">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Galeri Momen Bahagia</span>
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-slate-900">Potret Kebahagiaan Kami</h2>
                    <p class="text-xs sm:text-sm text-slate-500">Klik foto untuk melihat dalam resolusi penuh.</p>
                </div>

                <!-- Gallery Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($invitation->galleries as $gallery)
                        <div 
                            @click="openLightbox('{{ $gallery->image_url }}', '{{ addslashes($gallery->caption ?? '') }}')"
                            class="aspect-square rounded-2xl overflow-hidden shadow-sm bg-slate-200 group cursor-pointer relative"
                        >
                            <img
                                src="{{ $gallery->image_url }}"
                                alt="{{ $gallery->caption ?? 'Foto Galeri' }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition duration-500"
                            >
                            <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                <div class="p-2.5 rounded-full bg-white/20 backdrop-blur text-white">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607zM10.5 7.5v6m3-3h-6" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 7. Section Pesan / Doa Pengantin -->
    @if($invitation->message)
        <section class="py-20 px-4 bg-white border-t border-slate-100">
            <div class="max-w-xl mx-auto text-center space-y-4">
                <svg class="w-10 h-10 text-rose-200 mx-auto" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                </svg>
                <p class="text-base sm:text-lg text-slate-600 font-serif italic leading-relaxed">
                    "{{ $invitation->message }}"
                </p>
                <p class="text-xs font-bold text-rose-600 uppercase tracking-wider pt-2">
                    &mdash; {{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}
                </p>
            </div>
        </section>
    @endif

    <!-- 8. Section Tanda Kasih / Amplop Digital -->
    @if(!empty($accountNumber))
        <section class="py-24 px-4 bg-rose-50/40 border-t border-slate-100">
            <div class="max-w-md mx-auto text-center space-y-8">
                <div class="space-y-2">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Tanda Kasih</span>
                    <h2 class="text-3xl font-serif font-bold text-slate-900">Amplop Digital</h2>
                    <p class="text-xs sm:text-sm text-slate-500 leading-relaxed">
                        Doa restu Anda merupakan karunia terindah bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat mengirimkan hadiah secara digital melalui:
                    </p>
                </div>

                <!-- Bank Card -->
                <div 
                    x-data="{ copied: false }"
                    class="p-6 bg-white rounded-3xl border border-rose-100 shadow-md space-y-4"
                >
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                            {{ !empty($bankName) ? $bankName : 'Rekening Bank' }}
                        </span>
                        <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-rose-100 text-rose-700">Digital Gift</span>
                    </div>

                    <div class="py-2">
                        <p class="text-2xl font-mono font-bold text-slate-900 tracking-wider">
                            {{ $accountNumber }}
                        </p>
                        <p class="text-xs text-slate-500 mt-1">
                            a.n. <strong class="text-slate-800">{{ $accountHolder }}</strong>
                        </p>
                    </div>

                    <button
                        type="button"
                        @click="
                            navigator.clipboard.writeText('{{ $accountNumber }}');
                            copied = true;
                            setTimeout(() => copied = false, 2500);
                        "
                        class="w-full py-2.5 rounded-xl font-bold text-xs transition flex items-center justify-center gap-2 shadow-sm"
                        :class="copied ? 'bg-emerald-600 text-white' : 'bg-slate-900 hover:bg-slate-800 text-white'"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0013.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 01-.75.75H9a.75.75 0 01-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 01-2.25 2.25H6.75A2.25 2.25 0 014.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 011.927-.184" />
                        </svg>
                        <span x-text="copied ? 'Nomor Rekening Tersalin!' : 'Salin Nomor Rekening'"></span>
                    </button>
                </div>
            </div>
        </section>
    @endif

    <!-- 9. Section RSVP Online -->
    <section id="rsvp" class="py-24 px-4 romantic-pattern border-t border-slate-100">
        <div class="max-w-md mx-auto space-y-8">
            <div class="text-center space-y-2">
                <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">RSVP Online</span>
                <h2 class="text-3xl font-serif font-bold text-slate-900">Konfirmasi Kehadiran</h2>
                <p class="text-xs text-slate-500">Bantu kami mempersiapkan jamuan terbaik dengan mengisi konfirmasi kehadiran di bawah ini.</p>
            </div>

            <!-- Form Card -->
            <div x-show="! rsvpSubmitted" class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8">
                <form @submit.prevent="submitRsvp" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            x-model="rsvpForm.name"
                            required
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500"
                            placeholder="Tuliskan nama Anda"
                        >
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            No. WhatsApp <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <input
                            type="tel"
                            x-model="rsvpForm.phone"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500"
                            placeholder="0812xxxxxxxx"
                        >
                    </div>

                    <!-- Attendance Radios -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                            Konfirmasi Kehadiran <span class="text-rose-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="confirmed" class="peer sr-only" required>
                                <div class="p-3 border-2 border-slate-200 rounded-xl text-center peer-checked:border-emerald-500 peer-checked:bg-emerald-50 transition">
                                    <span class="block text-xs font-bold text-slate-700 peer-checked:text-emerald-800">Hadir</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="declined" class="peer sr-only">
                                <div class="p-3 border-2 border-slate-200 rounded-xl text-center peer-checked:border-rose-500 peer-checked:bg-rose-50 transition">
                                    <span class="block text-xs font-bold text-slate-700 peer-checked:text-rose-800">Tidak</span>
                                </div>
                            </label>
                            <label class="relative cursor-pointer">
                                <input type="radio" x-model="rsvpForm.attendance" value="pending" class="peer sr-only">
                                <div class="p-3 border-2 border-slate-200 rounded-xl text-center peer-checked:border-amber-500 peer-checked:bg-amber-50 transition">
                                    <span class="block text-xs font-bold text-slate-700 peer-checked:text-amber-800">Ragu</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Ucapan & Doa Restu <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                        </label>
                        <textarea
                            x-model="rsvpForm.message"
                            rows="3"
                            class="w-full px-4 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500"
                            placeholder="Berikan ucapan selamat untuk kedua mempelai..."
                        ></textarea>
                    </div>

                    <!-- Submit -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            :disabled="rsvpLoading"
                            class="w-full py-3.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-xl shadow-md hover:from-rose-600 hover:to-pink-600 transition disabled:opacity-50"
                        >
                            <span x-show="! rsvpLoading">Kirim Konfirmasi Kehadiran</span>
                            <span x-show="rsvpLoading" class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            </span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Success State Message -->
            <div x-show="rsvpSubmitted" x-cloak class="bg-white rounded-3xl shadow-xl border border-slate-100 p-8 text-center space-y-3">
                <div class="w-16 h-16 mx-auto rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900">Terima Kasih Banyak!</h3>
                <p class="text-xs sm:text-sm text-slate-600">
                    Konfirmasi kehadiran dan doa restu Anda telah kami simpan. Kehadiran Anda adalah kebahagiaan kami.
                </p>
            </div>
        </div>
    </section>

    <!-- 10. Section Ucapan Doa Restu Tamu -->
    @if($wishes->count() > 0)
        <section class="py-20 px-4 bg-white border-t border-slate-100">
            <div class="max-w-xl mx-auto space-y-8">
                <div class="text-center space-y-2">
                    <span class="text-xs font-bold text-rose-500 uppercase tracking-widest">Ucapan Doa</span>
                    <h2 class="text-3xl font-serif font-bold text-slate-900">Doa Restu Tamu Undangan</h2>
                </div>

                <div class="space-y-3 max-h-96 overflow-y-auto pr-1">
                    @foreach($wishes as $wish)
                        <div class="p-4 bg-rose-50/40 rounded-2xl border border-rose-100/60 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-sm text-slate-900">{{ $wish->name }}</span>
                                <span class="text-[11px] text-slate-400">{{ $wish->responded_at?->diffForHumans() }}</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-600 italic">"{{ $wish->message }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- 11. Footer & Share Section -->
    <footer class="py-12 px-4 bg-slate-900 text-white border-t border-slate-800">
        <div class="max-w-md mx-auto text-center space-y-6">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Bagikan Undangan Ini</p>
                <div class="flex items-center justify-center gap-3">
                    <!-- WhatsApp Share -->
                    <a
                        href="https://wa.me/?text={{ urlencode('Halo! Kami mengundang Anda menghadiri pernikahan ' . $invitation->groom_name . ' & ' . $invitation->bride_name . '. Akses undangan digital di: ' . $invitation->public_url) }}"
                        target="_blank"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-full transition shadow-sm"
                    >
                        <span>Bagikan ke WhatsApp</span>
                    </a>

                    <!-- Copy Link -->
                    <button
                        type="button"
                        @click="copyLink"
                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white font-bold text-xs rounded-full transition border border-slate-700"
                    >
                        <span x-text="linkCopied ? 'Tersalin!' : 'Salin Link Undangan'"></span>
                    </button>
                </div>
            </div>

            <p class="text-[11px] text-slate-500 pt-4 border-t border-slate-800">
                Dibuat dengan penuh cinta menggunakan platform <a href="/" class="text-rose-400 font-bold hover:underline">UndanginAja</a>
            </p>
        </div>
    </footer>

    <!-- Interactive Lightbox Modal -->
    <div 
        x-show="lightboxOpen" 
        x-cloak 
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/90 backdrop-blur-md" 
        @click.self="lightboxOpen = false" 
        @keydown.escape.window="lightboxOpen = false"
    >
        <button 
            type="button"
            @click="lightboxOpen = false" 
            class="absolute top-4 right-4 p-2.5 rounded-full bg-white/10 hover:bg-white/20 text-white transition z-10"
            title="Tutup (Esc)"
        >
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="max-w-4xl max-h-[90vh] flex flex-col items-center select-none">
            <img :src="lightboxImage" class="max-w-full max-h-[80vh] rounded-2xl object-contain shadow-2xl">
            <p x-show="lightboxCaption" x-text="lightboxCaption" class="mt-3 text-sm text-white/90 font-medium text-center"></p>
        </div>
    </div>

    <script>
        function invitationApp() {
            return {
                rsvpForm: {
                    name: '',
                    phone: '',
                    attendance: '',
                    message: ''
                },
                rsvpLoading: false,
                rsvpSubmitted: false,
                linkCopied: false,
                lightboxOpen: false,
                lightboxImage: '',
                lightboxCaption: '',
                musicUrl: '{{ $bgMusic }}',
                isPlaying: false,
                audio: null,

                init() {
                    if (this.musicUrl) {
                        this.audio = new Audio(this.musicUrl);
                        this.audio.loop = true;

                        const startPlay = () => {
                            if (!this.isPlaying && this.audio) {
                                this.audio.play().then(() => {
                                    this.isPlaying = true;
                                }).catch(() => {});
                            }
                            window.removeEventListener('click', startPlay);
                            window.removeEventListener('scroll', startPlay);
                        };

                        window.addEventListener('click', startPlay, { once: true });
                        window.addEventListener('scroll', startPlay, { once: true });
                    }
                },

                toggleMusic() {
                    if (!this.audio) return;
                    if (this.isPlaying) {
                        this.audio.pause();
                        this.isPlaying = false;
                    } else {
                        this.audio.play().then(() => {
                            this.isPlaying = true;
                        }).catch(e => console.error(e));
                    }
                },

                openLightbox(img, caption) {
                    this.lightboxImage = img;
                    this.lightboxCaption = caption;
                    this.lightboxOpen = true;
                },

                async submitRsvp() {
                    if (! this.rsvpForm.name || ! this.rsvpForm.attendance) return;

                    this.rsvpLoading = true;

                    try {
                        const response = await fetch('{{ route("public.rsvp", $invitation->slug) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify(this.rsvpForm)
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.rsvpSubmitted = true;
                        } else {
                            alert(data.message || 'Gagal mengirim konfirmasi RSVP.');
                        }
                    } catch (error) {
                        console.error('RSVP submission error:', error);
                        alert('Terjadi kesalahan koneksi.');
                    } finally {
                        this.rsvpLoading = false;
                    }
                },

                copyLink() {
                    navigator.clipboard.writeText('{{ $invitation->public_url }}');
                    this.linkCopied = true;
                    setTimeout(() => this.linkCopied = false, 2000);
                }
            }
        }

        function countdownTimer(targetDateStr) {
            return {
                target: new Date(targetDateStr).getTime(),
                days: 0,
                hours: 0,
                minutes: 0,
                seconds: 0,
                timer: null,

                init() {
                    this.updateCountdown();
                    this.timer = setInterval(() => {
                        this.updateCountdown();
                    }, 1000);
                },

                updateCountdown() {
                    const now = new Date().getTime();
                    const diff = this.target - now;

                    if (diff <= 0) {
                        this.days = 0;
                        this.hours = 0;
                        this.minutes = 0;
                        this.seconds = 0;
                        if (this.timer) clearInterval(this.timer);
                        return;
                    }

                    this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                }
            }
        }
    </script>
</body>
</html>
