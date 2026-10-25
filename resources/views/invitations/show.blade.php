@extends('layouts.dashboard')

@section('title', 'Preview Undangan')

@section('actions')
    <div class="flex items-center gap-2.5">
        <a href="{{ $invitation->public_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-rose-100 transition shadow-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
            <span>Buka Website Undangan</span>
        </a>
        <a href="{{ route('invitations.edit', $invitation) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125" />
            </svg>
            <span>Edit Undangan</span>
        </a>

        <form action="{{ route('invitations.togglePublish', $invitation) }}" method="POST">
            @csrf
            @if($invitation->is_published)
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-amber-100 transition">
                    <span>Unpublish</span>
                </button>
            @else
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <span>Publish Sekarang</span>
                </button>
            @endif
        </form>
    </div>
@endsection

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Status Banner -->
    @if($invitation->is_published)
        <div class="p-5 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-start gap-3.5" x-data="{ copied: false }">
            <div class="w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-emerald-900">Undangan ini sedang Aktif (Live)!</p>
                <p class="text-xs text-emerald-700 mt-0.5">Tamu dapat mengakses dan mengisi RSVP melalui link publik:</p>
                <div class="mt-2.5 flex items-center gap-2">
                    <input
                        type="text"
                        value="{{ $invitation->public_url }}"
                        readonly
                        class="flex-1 px-3 py-1.5 text-xs bg-white border border-emerald-200 rounded-lg text-slate-700 truncate"
                    >
                    <button
                        type="button"
                        @click="navigator.clipboard.writeText('{{ $invitation->public_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                        class="px-3 py-1.5 text-xs font-semibold bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition"
                    >
                        <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                    </button>
                    <a href="{{ $invitation->public_url }}" target="_blank" class="px-3 py-1.5 text-xs font-semibold bg-white border border-emerald-300 text-emerald-700 rounded-lg hover:bg-emerald-50 transition">
                        Buka
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-amber-800 text-xs sm:text-sm">
            <div class="flex items-center gap-3">
                <svg class="w-5 h-5 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                </svg>
                <span><strong class="font-bold">Mode Preview:</strong> Undangan masih berstatus Draft dan belum dapat diakses oleh publik.</span>
            </div>
            <a href="{{ $invitation->public_url }}" target="_blank" class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 bg-amber-600 hover:bg-amber-700 text-white font-semibold rounded-lg text-xs transition shadow-sm shrink-0">
                <span>Preview Template Penuh &rarr;</span>
            </a>
        </div>
    @endif

    <!-- Invitation Card Preview Simulator -->
    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
        <!-- Header Banner with gradient -->
        <div class="h-36 bg-gradient-to-r from-rose-400 via-pink-400 to-rose-300 flex items-center justify-center relative">
            <span class="text-white/90 text-xs font-semibold tracking-widest uppercase">Undangan Digital &bull; UndanginAja</span>
        </div>

        <!-- Inner Content -->
        <div class="p-8 -mt-10 space-y-6 text-center relative z-10">
            <div class="inline-block bg-white rounded-2xl shadow-md border border-slate-100 px-8 py-6 max-w-sm mx-auto">
                <p class="text-xs text-rose-500 font-semibold tracking-widest uppercase">The Wedding of</p>
                <h1 class="mt-2 text-2xl sm:text-3xl font-serif font-bold text-slate-900">{{ $invitation->groom_name }}</h1>
                <p class="my-1 text-xl font-serif text-rose-400">&</p>
                <h1 class="text-2xl sm:text-3xl font-serif font-bold text-slate-900">{{ $invitation->bride_name }}</h1>
            </div>

            <div class="py-6 border-y border-slate-100 space-y-1">
                <p class="text-base sm:text-lg font-bold text-slate-800">
                    {{ $invitation->event_date?->format('l, d F Y') }}
                </p>
                <p class="text-sm text-slate-500 font-medium">
                    Pukul {{ $invitation->event_time?->format('H:i') ?? '09:00' }} WIB
                </p>
            </div>

            <div class="space-y-1">
                <p class="font-bold text-slate-900">{{ $invitation->venue_name }}</p>
                <p class="text-xs sm:text-sm text-slate-600 max-w-md mx-auto">{{ $invitation->venue_address }}</p>
                @if($invitation->google_maps_url)
                    <div class="pt-2">
                        <a href="{{ $invitation->google_maps_url }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-semibold text-rose-600 hover:text-rose-700 transition">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            <span>Buka di Google Maps</span>
                        </a>
                    </div>
                @endif
            </div>

            @if($invitation->message)
                <div class="p-5 bg-rose-50/50 rounded-2xl border border-rose-100/60 max-w-md mx-auto">
                    <p class="text-xs sm:text-sm text-slate-600 italic">"{{ $invitation->message }}"</p>
                </div>
            @endif

            <!-- Mini Guest RSVP Stats -->
            <div class="pt-6 border-t border-slate-100">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Statistik Kehadiran Tamu</p>
                <div class="grid grid-cols-3 gap-2 max-w-xs mx-auto">
                    <div class="p-3 bg-slate-50 rounded-xl">
                        <p class="text-lg font-bold text-slate-900">{{ $invitation->guests->count() }}</p>
                        <p class="text-[11px] text-slate-500 font-medium">Total RSVP</p>
                    </div>
                    <div class="p-3 bg-emerald-50 rounded-xl">
                        <p class="text-lg font-bold text-emerald-600">{{ $invitation->guests()->confirmed()->count() }}</p>
                        <p class="text-[11px] text-emerald-700 font-medium">Hadir</p>
                    </div>
                    <div class="p-3 bg-rose-50 rounded-xl">
                        <p class="text-lg font-bold text-rose-600">{{ $invitation->guests()->declined()->count() }}</p>
                        <p class="text-[11px] text-rose-700 font-medium">Tidak Hadir</p>
                    </div>
                </div>
                <div class="mt-4">
                    <a href="{{ route('guests.index', $invitation) }}" class="text-xs font-semibold text-rose-600 hover:underline">
                        Lihat & Ekspor Semua Data Tamu &rarr;
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
