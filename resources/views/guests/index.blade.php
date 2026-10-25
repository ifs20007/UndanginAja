@extends('layouts.dashboard')

@section('title', 'Daftar Tamu & RSVP')

@section('actions')
    <a
        href="{{ route('guests.export', $invitation) }}"
        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-emerald-100 transition shadow-sm"
    >
        <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.5V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        <span>Ekspor ke CSV</span>
    </a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Breadcrumb & Title -->
    <div>
        <a href="{{ route('invitations.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-slate-800 transition mb-2">
            &larr; Kembali ke Undangan Saya
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Daftar Tamu & Respon RSVP</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
            Undangan Pernikahan: <span class="font-bold text-slate-800">{{ $invitation->groom_name }} & {{ $invitation->bride_name }}</span>
        </p>
    </div>

    <!-- Filter Tabs & Search Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <!-- Tabs -->
        <div class="flex flex-wrap items-center gap-2">
            <a
                href="{{ route('guests.index', ['invitation' => $invitation, 'attendance' => 'all']) }}"
                class="px-3.5 py-2 text-xs font-bold rounded-xl transition {{ $currentAttendance === 'all' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
            >
                Semua ({{ $stats['total'] }})
            </a>
            <a
                href="{{ route('guests.index', ['invitation' => $invitation, 'attendance' => 'confirmed']) }}"
                class="px-3.5 py-2 text-xs font-bold rounded-xl transition {{ $currentAttendance === 'confirmed' ? 'bg-emerald-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
            >
                Hadir ({{ $stats['confirmed'] }})
            </a>
            <a
                href="{{ route('guests.index', ['invitation' => $invitation, 'attendance' => 'declined']) }}"
                class="px-3.5 py-2 text-xs font-bold rounded-xl transition {{ $currentAttendance === 'declined' ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
            >
                Tidak Hadir ({{ $stats['declined'] }})
            </a>
            <a
                href="{{ route('guests.index', ['invitation' => $invitation, 'attendance' => 'pending']) }}"
                class="px-3.5 py-2 text-xs font-bold rounded-xl transition {{ $currentAttendance === 'pending' ? 'bg-amber-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
            >
                Masih Ragu ({{ $stats['pending'] }})
            </a>
        </div>

        <!-- Search Form -->
        <form action="{{ route('guests.index', $invitation) }}" method="GET" class="flex items-center gap-2">
            <input type="hidden" name="attendance" value="{{ $currentAttendance }}">
            <div class="relative w-full sm:w-60">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $currentSearch }}"
                    placeholder="Cari nama tamu..."
                    class="pl-9 pr-4 py-2 w-full text-xs sm:text-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                >
            </div>
            <button type="submit" class="px-3.5 py-2 bg-slate-900 text-white text-xs font-semibold rounded-xl hover:bg-slate-800 transition">
                Cari
            </button>
        </form>
    </div>

    <!-- Guests Table -->
    @if($guests->count() > 0)
        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs sm:text-sm">
                    <thead class="bg-slate-50/80 border-b border-slate-100 text-slate-500 font-semibold uppercase tracking-wider text-[11px]">
                        <tr>
                            <th class="px-6 py-4">Nama Tamu</th>
                            <th class="px-6 py-4">WhatsApp / Telp</th>
                            <th class="px-6 py-4">Konfirmasi Kehadiran</th>
                            <th class="px-6 py-4">Ucapan & Doa</th>
                            <th class="px-6 py-4">Waktu RSVP</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($guests as $guest)
                            <tr class="hover:bg-slate-50/60 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-rose-50 text-rose-600 font-bold text-xs flex items-center justify-center shrink-0">
                                            {{ strtoupper(substr($guest->name, 0, 1)) }}
                                        </div>
                                        <span class="font-bold text-slate-900">{{ $guest->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-mono text-xs">
                                    {{ $guest->phone ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @php
                                        $attVal = is_object($guest->attendance) ? $guest->attendance->value : (string) $guest->attendance;
                                    @endphp
                                    @if(in_array($attVal, ['confirmed', 'attending']))
                                        <span class="px-2.5 py-1 text-xs font-bold bg-emerald-100 text-emerald-700 rounded-full">Hadir</span>
                                    @elseif(in_array($attVal, ['declined', 'not_attending']))
                                        <span class="px-2.5 py-1 text-xs font-bold bg-rose-100 text-rose-700 rounded-full">Tidak Hadir</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold bg-amber-100 text-amber-700 rounded-full">Masih Ragu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-slate-600 max-w-xs truncate italic">
                                    "{{ $guest->message ?? '-' }}"
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-400">
                                    {{ $guest->responded_at?->format('d/m/Y H:i') ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('guests.destroy', $guest) }}" method="POST" onsubmit="return confirm('Hapus data respon tamu ini?')" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 transition" title="Hapus Tamu">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($guests->hasPages())
                <div class="px-6 py-4 border-t border-slate-100">
                    {{ $guests->links() }}
                </div>
            @endif
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center max-w-lg mx-auto">
            <div class="w-16 h-16 mx-auto rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mb-4">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Belum Ada Respon Tamu</h3>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">Daftar tamu akan otomatis terisi saat undangan publik disebarkan dan tamu mengisi form RSVP.</p>
        </div>
    @endif
</div>
@endsection
