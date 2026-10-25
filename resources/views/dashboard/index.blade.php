@extends('layouts.dashboard')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <!-- Welcome Heading -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Selamat datang kembali, {{ auth()->user()->name }}! 👋</h2>
            <p class="mt-1 text-slate-600">Berikut ikhtisar performa dan aktivitas undangan pernikahan digital Anda.</p>
        </div>
        <div>
            <a 
                href="{{ route('invitations.create') }}" 
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold text-sm rounded-xl shadow-sm hover:from-rose-600 hover:to-pink-600 transition"
            >
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Buat Undangan Baru</span>
            </a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Total Invitations -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_invitations'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Total Undangan</p>
            </div>
        </div>

        <!-- Published -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['published_invitations'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Published (Live)</p>
            </div>
        </div>

        <!-- Draft -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['draft_invitations'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Draft</p>
            </div>
        </div>

        <!-- Total Guests / RSVP -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-slate-900">{{ $stats['total_guests'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Total RSVP Masuk</p>
            </div>
        </div>

        <!-- Confirmed Hadir -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-emerald-600">{{ $stats['confirmed_guests'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Hadir</p>
            </div>
        </div>

        <!-- Declined -->
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-rose-600">{{ $stats['declined_guests'] }}</p>
                <p class="text-xs sm:text-sm text-slate-500 font-medium">Tidak Hadir</p>
            </div>
        </div>
    </div>

    <!-- Charts & Recent Activity Row -->
    <div class="grid lg:grid-cols-2 gap-6">
        <!-- Attendance Chart & Breakdown -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <h3 class="text-lg font-bold text-slate-900 mb-4">Statistik Kehadiran RSVP</h3>

            @if($stats['total_guests'] > 0)
                <div class="space-y-4">
                    <!-- Hadir -->
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600 font-medium flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                                Hadir
                            </span>
                            <span class="font-semibold text-slate-900">{{ $stats['confirmed_guests'] }} ({{ $chartData['confirmed'] }}%)</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-emerald-500 rounded-full transition-all duration-500" style="width: {{ $chartData['confirmed'] }}%"></div>
                        </div>
                    </div>

                    <!-- Tidak Hadir -->
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600 font-medium flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-rose-500"></span>
                                Tidak Hadir
                            </span>
                            <span class="font-semibold text-slate-900">{{ $stats['declined_guests'] }} ({{ $chartData['declined'] }}%)</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-rose-500 rounded-full transition-all duration-500" style="width: {{ $chartData['declined'] }}%"></div>
                        </div>
                    </div>

                    <!-- Masih Ragu / Pending -->
                    <div>
                        <div class="flex justify-between text-sm mb-1">
                            <span class="text-slate-600 font-medium flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full bg-amber-500"></span>
                                Masih Ragu
                            </span>
                            <span class="font-semibold text-slate-900">{{ $stats['pending_guests'] }} ({{ $chartData['pending'] }}%)</span>
                        </div>
                        <div class="h-2.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full transition-all duration-500" style="width: {{ $chartData['pending'] }}%"></div>
                        </div>
                    </div>
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-slate-400 text-sm">Belum ada respon RSVP dari tamu undangan.</p>
                </div>
            @endif
        </div>

        <!-- Recent RSVPs -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Respon RSVP Terbaru</h3>
            </div>

            @if($recentRsvps->count() > 0)
                <div class="space-y-3">
                    @foreach($recentRsvps as $rsvp)
                        <div class="flex items-center gap-3 p-3 bg-slate-50/70 rounded-xl border border-slate-100/60">
                            <div class="w-9 h-9 rounded-full bg-rose-100 text-rose-600 font-bold text-xs flex items-center justify-center shrink-0">
                                {{ strtoupper(substr($rsvp->name, 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-slate-900 truncate">{{ $rsvp->name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $rsvp->invitation->groom_name }} & {{ $rsvp->invitation->bride_name }}</p>
                            </div>
                            <div class="text-right shrink-0">
                                @php
                                    $att = is_object($rsvp->attendance) ? $rsvp->attendance->value : (string) $rsvp->attendance;
                                @endphp
                                @if(in_array($att, ['confirmed', 'attending']))
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">Hadir</span>
                                @elseif(in_array($att, ['declined', 'not_attending']))
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-rose-100 text-rose-700 rounded-full">Tidak</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">Ragu</span>
                                @endif
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $rsvp->responded_at?->diffForHumans() }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-10">
                    <p class="text-slate-400 text-sm">Belum ada tamu yang merespon undangan.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Recent Invitations -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-slate-900">Undangan Terbaru</h3>
            <a href="{{ route('invitations.index') }}" class="text-xs sm:text-sm font-semibold text-rose-600 hover:text-rose-700">Lihat Semua Undangan &rarr;</a>
        </div>

        @if($recentInvitations->count() > 0)
            <div class="grid sm:grid-cols-3 gap-4">
                @foreach($recentInvitations as $invitation)
                    <a href="{{ route('invitations.edit', $invitation) }}" class="p-4 bg-slate-50/70 rounded-xl hover:bg-slate-100/80 border border-slate-100 transition group">
                        <p class="font-semibold text-slate-900 group-hover:text-rose-600 transition truncate">{{ $invitation->groom_name }} & {{ $invitation->bride_name }}</p>
                        <p class="text-xs text-slate-500 mt-1">{{ $invitation->event_date?->format('d M Y') }}</p>
                        <div class="mt-3">
                            @if($invitation->is_published)
                                <span class="px-2 py-0.5 text-[11px] font-bold bg-emerald-100 text-emerald-700 rounded-full">Published</span>
                            @else
                                <span class="px-2 py-0.5 text-[11px] font-bold bg-slate-200 text-slate-600 rounded-full">Draft</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <div class="text-center py-8">
                <p class="text-slate-400 text-sm mb-3">Anda belum memiliki undangan yang dibuat.</p>
                <a href="{{ route('invitations.create') }}" class="text-rose-600 font-semibold text-sm hover:underline">Mulai Buat Undangan Pertama</a>
            </div>
        @endif
    </div>
</div>
@endsection
