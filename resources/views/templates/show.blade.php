@extends('layouts.dashboard')

@section('title', 'Preview Template - ' . $template->name)

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Back Navigation -->
    <a href="{{ route('templates.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
        &larr; Kembali ke Katalog Template
    </a>

    <div class="grid lg:grid-cols-3 gap-8">
        <!-- Preview Canvas -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                    <!-- Header Banner -->
                    <div class="h-32 bg-gradient-to-r from-rose-400 via-pink-400 to-rose-300 flex items-center justify-center">
                        <p class="text-white/90 text-xs font-semibold uppercase tracking-widest">Simulasi Desain: {{ $template->name }}</p>
                    </div>

                    <!-- Simulation Content -->
                    <div class="p-8 -mt-8 space-y-6 text-center">
                        <div class="inline-block bg-white rounded-2xl shadow-md border border-slate-100 px-8 py-6 max-w-sm mx-auto">
                            <p class="text-xs text-rose-500 font-semibold tracking-widest uppercase">The Wedding of</p>
                            <h1 class="mt-2 text-3xl font-serif font-bold text-slate-900">{{ $dummyData['groom_name'] }}</h1>
                            <p class="my-1 text-xl font-serif text-rose-400">&</p>
                            <h1 class="text-3xl font-serif font-bold text-slate-900">{{ $dummyData['bride_name'] }}</h1>
                        </div>

                        <div class="py-6 border-y border-slate-100 space-y-1">
                            <p class="text-base sm:text-lg font-bold text-slate-800">
                                {{ $dummyData['event_date']->format('l, d F Y') }}
                            </p>
                            <p class="text-sm text-slate-500 font-medium">Pukul {{ $dummyData['event_time'] }} WIB</p>
                        </div>

                        <div class="space-y-1">
                            <p class="font-bold text-slate-900">{{ $dummyData['venue_name'] }}</p>
                            <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">{{ $dummyData['venue_address'] }}</p>
                        </div>

                        <div class="p-5 bg-rose-50/50 rounded-2xl border border-rose-100/60 max-w-md mx-auto">
                            <p class="text-xs sm:text-sm text-slate-600 italic leading-relaxed">
                                "{{ $dummyData['message'] }}"
                            </p>
                        </div>

                        <div class="pt-2">
                            <span class="inline-block px-6 py-2.5 bg-rose-600 text-white font-semibold text-xs rounded-full shadow-sm">
                                Konfirmasi Kehadiran (RSVP)
                            </span>
                        </div>
                    </div>

                    <div class="p-4 bg-slate-50 border-t border-slate-100 text-center">
                        <p class="text-xs text-slate-400">
                            Pratinjau di atas menggunakan data simulasi. Teks dan detail acara akan otomatis menyesuaikan data pengantin Anda.
                        </p>
                    </div>
                </div>
        </div>

        <!-- Sidebar Info Card -->
        <div class="space-y-6">
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 space-y-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $template->name }}</h2>
                    <p class="text-xs text-slate-500 mt-1">Didesain eksklusif dengan tema interaktif, animasi romantis &amp; musik.</p>
                </div>

                <div class="space-y-3 pt-2 border-t border-slate-100 text-sm">
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Kategori</span>
                        @php
                            $catVal = is_object($template->category) ? $template->category->value : (string) $template->category;
                        @endphp
                        <span class="font-semibold text-slate-900 capitalize">{{ $catVal }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-slate-500">Tipe Akses</span>
                        @if($template->is_premium)
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">
                                Premium
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                Gratis
                            </span>
                        @endif
                    </div>
                </div>

                <div class="pt-2 border-t border-slate-100 space-y-3">
                    <a 
                        href="{{ route('templates.preview', $template) }}" 
                        target="_blank" 
                        class="block w-full py-2.5 px-4 bg-slate-900 text-white text-center font-bold text-xs rounded-xl hover:bg-slate-800 transition shadow-sm"
                    >
                        Buka Live Preview Layar Penuh &nearr;
                    </a>

                    @if($template->is_premium && ! $canUsePremium)
                        <div class="text-center space-y-3 pt-2">
                            <p class="text-xs text-slate-600">
                                Template ini merupakan koleksi premium. Silakan upgrade ke paket Pro untuk menggunakan template ini.
                            </p>
                            <a
                                href="{{ route('subscription.index') }}"
                                class="block w-full px-4 py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-xl text-center shadow-sm hover:from-rose-600 hover:to-pink-600 transition"
                            >
                                Upgrade ke Pro Sekarang
                            </a>
                        </div>
                    @else
                        <div x-data="{ showModal: false }">
                            <button
                                type="button"
                                @click="showModal = true"
                                class="w-full px-4 py-3 bg-rose-600 text-white font-bold text-sm rounded-xl hover:bg-rose-700 transition shadow-sm"
                            >
                                Gunakan Template Ini
                            </button>

                            <!-- Modal apply -->
                            <div
                                x-show="showModal"
                                x-cloak
                                class="fixed inset-0 z-50 overflow-y-auto"
                            >
                                <div class="flex min-h-screen items-center justify-center p-4">
                                    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showModal = false"></div>

                                    <div class="relative bg-white rounded-3xl shadow-xl max-w-md w-full p-6 text-left z-10">
                                        <h3 class="text-lg font-bold text-slate-900 mb-2">Pilih Undangan</h3>
                                        <p class="text-xs text-slate-600 mb-4">Pilih undangan yang ingin dipasangkan tema template ini.</p>

                                        <form action="{{ route('templates.apply') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="template_id" value="{{ $template->id }}">

                                            <div class="mb-4">
                                                <select
                                                    name="invitation_id"
                                                    required
                                                    class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500"
                                                >
                                                    <option value="">-- Pilih Undangan --</option>
                                                    @foreach(auth()->user()->invitations as $inv)
                                                        <option value="{{ $inv->id }}">
                                                            {{ $inv->groom_name }} & {{ $inv->bride_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="flex gap-3">
                                                <button
                                                    type="button"
                                                    @click="showModal = false"
                                                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200"
                                                >
                                                    Batal
                                                </button>
                                                <button
                                                    type="submit"
                                                    class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 shadow-sm"
                                                >
                                                    Terapkan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Tips Card -->
            <div class="bg-rose-50/60 rounded-3xl p-5 border border-rose-100/80">
                <h4 class="text-xs font-bold text-rose-900 uppercase tracking-wider mb-2">Keunggulan Template</h4>
                <ul class="text-xs text-slate-600 space-y-1.5">
                    <li>&bull; Responsif di semua ukuran layar smartphone & tablet.</li>
                    <li>&bull; Dilengkapi pemutar musik, animasi confetti, &amp; countdown timer.</li>
                    <li>&bull; Terintegrasi langsung dengan sistem RSVP &amp; amplop digital SaaS.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection