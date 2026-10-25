@extends('layouts.dashboard')

@section('title', 'Undangan Saya')

@section('actions')
    @php
        $user = auth()->user();
        $currentPlan = $user->current_plan;
        $canCreate = $user->canCreateInvitation();
    @endphp

    @if($canCreate)
        <a href="{{ route('invitations.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-sm font-semibold rounded-xl shadow-sm hover:from-rose-600 hover:to-pink-600 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Buat Undangan Baru</span>
        </a>
    @else
        <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold rounded-xl hover:bg-amber-100 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
            </svg>
            <span>Upgrade untuk Tambah Kuota</span>
        </a>
    @endif
@endsection

@section('content')
<div class="space-y-6" x-data="{ deleteModalOpen: false, deleteActionUrl: '', deleteItemName: '' }">
    
    <!-- Info Quota & Search Filter Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-slate-600">
                @php
                    $user = auth()->user();
                    $currentPlan = $user->current_plan;
                @endphp
                @if($currentPlan)
                    Kuota Digunakan: <span class="font-bold text-slate-900">{{ $user->invitations()->count() }}</span> dari 
                    <span class="font-bold text-slate-900">{{ $currentPlan->max_invitations == 999 ? '∞ Unlimited' : $currentPlan->max_invitations }}</span> undangan
                    <span class="ml-1 px-2 py-0.5 text-xs font-semibold rounded-md bg-rose-50 text-rose-700">Paket {{ $currentPlan->name }}</span>
                @endif
            </p>
        </div>

        <!-- Filter Form -->
        <form action="{{ route('invitations.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2.5">
            <div class="relative w-full sm:w-64">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
                <input
                    type="text"
                    name="search"
                    value="{{ $currentSearch }}"
                    placeholder="Cari pengantin..."
                    class="pl-9 pr-4 py-2 w-full text-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                >
            </div>

            <select
                name="status"
                onchange="this.form.submit()"
                class="px-3.5 py-2 w-full sm:w-auto text-sm bg-white border border-slate-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
            >
                <option value="">Semua Status</option>
                <option value="published" {{ $currentStatus === 'published' ? 'selected' : '' }}>Published</option>
                <option value="draft" {{ $currentStatus === 'draft' ? 'selected' : '' }}>Draft</option>
            </select>

            @if($currentSearch || $currentStatus)
                <a href="{{ route('invitations.index') }}" class="px-3 py-2 text-xs font-medium text-slate-500 hover:text-slate-800 transition">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Invitations Cards Grid -->
    @if($invitations->count() > 0)
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($invitations as $invitation)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden group hover:shadow-md transition">
                    
                    <!-- Cover / Thumbnail -->
                    <div class="aspect-[16/10] bg-gradient-to-tr from-rose-100 to-pink-50 relative overflow-hidden">
                        @if($invitation->template && $invitation->template->thumbnail)
                            <img
                                src="{{ $invitation->template->thumbnail }}"
                                alt="{{ $invitation->template->name }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                            >
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 gap-2">
                                <svg class="w-10 h-10 text-rose-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                </svg>
                                <span class="text-xs text-rose-400 font-medium">Template Default</span>
                            </div>
                        @endif

                        <!-- Publication Badge -->
                        <div class="absolute top-3 left-3">
                            @if($invitation->is_published)
                                <span class="px-2.5 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-xs font-bold rounded-full shadow-sm">
                                    Published
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-slate-600/90 backdrop-blur-sm text-white text-xs font-bold rounded-full shadow-sm">
                                    Draft
                                </span>
                            @endif
                        </div>

                        <!-- Hover Overlay Actions -->
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <a href="{{ route('invitations.edit', $invitation) }}" class="p-2.5 bg-white text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition shadow-sm" title="Edit Undangan">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </a>
                            <a href="{{ route('invitations.show', $invitation) }}" class="p-2.5 bg-white text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition shadow-sm" title="Preview Undangan">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </a>
                            <a href="{{ route('guests.index', $invitation) }}" class="p-2.5 bg-white text-slate-700 rounded-xl hover:bg-slate-100 hover:text-slate-900 transition shadow-sm" title="Kelola Daftar Tamu">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </a>
                            <button 
                                type="button" 
                                @click="deleteModalOpen = true; deleteActionUrl = '{{ route('invitations.destroy', $invitation) }}'; deleteItemName = '{{ $invitation->groom_name }} & {{ $invitation->bride_name }}'"
                                class="p-2.5 bg-white text-rose-600 rounded-xl hover:bg-rose-50 transition shadow-sm" 
                                title="Hapus Undangan"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="p-5 space-y-3">
                        <div>
                            <h3 class="font-bold text-slate-900 text-base truncate">
                                {{ $invitation->groom_name }} & {{ $invitation->bride_name }}
                            </h3>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                <span>{{ $invitation->event_date?->format('d F Y') }}</span>
                            </p>
                        </div>

                        <!-- Public Link Copy Button if Published -->
                        @if($invitation->is_published)
                            <div class="pt-2 border-t border-slate-100 flex items-center gap-2" x-data="{ copied: false }">
                                <input
                                    type="text"
                                    value="{{ $invitation->public_url }}"
                                    readonly
                                    class="flex-1 px-3 py-1.5 text-xs bg-slate-50 border border-slate-200 rounded-lg text-slate-600 truncate focus:outline-none"
                                >
                                <button
                                    type="button"
                                    @click="navigator.clipboard.writeText('{{ $invitation->public_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="px-2.5 py-1.5 text-xs font-semibold rounded-lg transition"
                                    :class="copied ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-700 hover:bg-slate-200'"
                                >
                                    <span x-text="copied ? 'Tersalin!' : 'Salin'"></span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-8">
            {{ $invitations->links() }}
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-3xl border border-slate-100 p-12 text-center max-w-lg mx-auto">
            <div class="w-20 h-20 mx-auto rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mb-5">
                <svg class="w-10 h-10" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-slate-900">Belum Ada Undangan</h3>
            <p class="mt-2 text-sm text-slate-500">
                Mulai ciptakan undangan pernikahan digital Anda dengan memilih template yang mempesona dan mengisi informasi acara.
            </p>
            @if($canCreate)
                <a href="{{ route('invitations.create') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold text-sm rounded-xl shadow-sm hover:from-rose-600 hover:to-pink-600 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Buat Undangan Pertama</span>
                </a>
            @endif
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div 
        x-show="deleteModalOpen" 
        x-cloak 
        class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" 
        role="dialog" 
        aria-modal="true"
    >
        <div class="flex min-h-screen items-center justify-center p-4">
            <!-- Backdrop -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="transition-opacity ease-linear duration-200"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition-opacity ease-linear duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm"
                @click="deleteModalOpen = false"
            ></div>

            <!-- Modal Content -->
            <div 
                x-show="deleteModalOpen"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative bg-white rounded-3xl shadow-xl max-w-md w-full p-6 text-center z-10"
            >
                <div class="w-14 h-14 mx-auto bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900" id="modal-title">Hapus Undangan?</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Undangan <span class="font-semibold text-slate-900" x-text="deleteItemName"></span> akan dihapus dari sistem.
                </p>

                <div class="mt-6 flex gap-3">
                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition"
                    >
                        Batal
                    </button>
                    <form :action="deleteActionUrl" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button 
                            type="submit" 
                            class="w-full px-4 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition shadow-sm"
                        >
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
