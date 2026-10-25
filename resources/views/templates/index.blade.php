@extends('layouts.dashboard')

@section('title', 'Katalog Template')

@section('content')
<div class="space-y-6" x-data="{ showApplyModal: false, selectedTemplateId: '', selectedTemplateName: '' }">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-900">Katalog Template Undangan</h2>
            <p class="mt-1 text-sm text-slate-600">Pilih tema desain eksklusif yang sesuai dengan konsep pernikahan impian Anda.</p>
        </div>
    </div>

    <!-- Category Filter Tabs -->
    <div class="flex flex-wrap items-center gap-2">
        <a
            href="{{ route('templates.index') }}"
            class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition {{ ! $currentCategory ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
        >
            Semua Template
        </a>
        @foreach($categories as $slug => $name)
            <a
                href="{{ route('templates.index', ['category' => $slug]) }}"
                class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl transition {{ $currentCategory === $slug ? 'bg-rose-600 text-white shadow-sm' : 'bg-white text-slate-700 border border-slate-200 hover:bg-slate-50' }}"
            >
                {{ $name }}
            </a>
        @endforeach
    </div>

    <!-- Templates Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($templates as $template)
            <div
                class="group relative bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden flex flex-col hover:shadow-md transition"
            >
                <!-- Thumbnail -->
                <div class="aspect-[3/4] bg-gradient-to-tr from-rose-50 to-pink-50 relative overflow-hidden">
                    @if($template->thumbnail)
                        <img
                            src="{{ $template->thumbnail }}"
                            alt="{{ $template->name }}"
                            class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center text-rose-300">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                    @endif

                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        @php
                            $catVal = is_object($template->category) ? $template->category->value : (string) $template->category;
                        @endphp
                        <span class="px-2.5 py-1 bg-white/90 backdrop-blur-sm text-[11px] font-bold text-slate-800 rounded-full shadow-sm capitalize">
                            {{ $catVal }}
                        </span>
                    </div>

                    <!-- Lock Overlay if Premium and not subscribed -->
                    @if($template->is_premium && ! $canUsePremium)
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-[2px] flex items-center justify-center p-4">
                            <div class="text-center text-white">
                                <div class="w-10 h-10 mx-auto rounded-full bg-white/20 flex items-center justify-center mb-1.5">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                    </svg>
                                </div>
                                <p class="text-xs font-bold">Khusus Pro</p>
                            </div>
                        </div>
                    @endif

                    <!-- Hover Actions -->
                    @if(! $template->is_premium || $canUsePremium)
                        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-[2px] flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity p-2">
                            <a
                                href="{{ route('templates.show', $template) }}"
                                class="px-3 py-2 bg-white text-slate-800 text-xs font-semibold rounded-xl hover:bg-slate-100 transition shadow-sm"
                            >
                                Preview
                            </a>
                            <button
                                type="button"
                                @click="showApplyModal = true; selectedTemplateId = '{{ $template->id }}'; selectedTemplateName = '{{ addslashes($template->name) }}'"
                                class="px-3 py-2 bg-rose-600 text-white text-xs font-semibold rounded-xl hover:bg-rose-700 transition shadow-sm"
                            >
                                Gunakan
                            </button>
                        </div>
                    @endif
                </div>

                <!-- Template Details -->
                <div class="p-4 flex-1 flex flex-col justify-between">
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm truncate">{{ $template->name }}</h3>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        @if($template->is_premium)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 text-[11px] font-bold bg-amber-100 text-amber-700 rounded-full">
                                <span>Premium</span>
                            </span>
                        @else
                            <span class="inline-block px-2.5 py-0.5 text-[11px] font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                Gratis
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Single Apply Modal (Outside Loop) -->
    <div
        x-show="showApplyModal"
        x-cloak
        class="fixed inset-0 z-50 overflow-y-auto"
    >
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showApplyModal = false"></div>

            <div class="relative bg-white rounded-3xl shadow-xl max-w-md w-full p-6 text-left z-10">
                <h3 class="text-lg font-bold text-slate-900 mb-1">Terapkan Template</h3>
                <p class="text-xs text-slate-600 mb-4">
                    Pilih undangan Anda yang ingin dipasangkan tema <span class="font-bold text-slate-900" x-text="selectedTemplateName"></span>.
                </p>

                <form action="{{ route('templates.apply') }}" method="POST">
                    @csrf
                    <input type="hidden" name="template_id" :value="selectedTemplateId">

                    <div class="mb-4">
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">Pilih Undangan</label>
                        <select
                            name="invitation_id"
                            required
                            class="w-full px-3.5 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                        >
                            <option value="">-- Pilih Undangan Anda --</option>
                            @foreach($userInvitations as $inv)
                                <option value="{{ $inv->id }}">
                                    {{ $inv->groom_name }} & {{ $inv->bride_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if($userInvitations->isEmpty())
                        <div class="mb-4 p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800">
                            Anda belum memiliki undangan. 
                            <a href="{{ route('invitations.create') }}" class="font-bold underline">Buat undangan sekarang</a>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button
                            type="button"
                            @click="showApplyModal = false"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            {{ $userInvitations->isEmpty() ? 'disabled' : '' }}
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-sm"
                        >
                            Terapkan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upgrade CTA Banner -->
    @if(! $canUsePremium)
        <div class="bg-gradient-to-r from-rose-600 via-pink-600 to-rose-500 rounded-3xl p-6 sm:p-8 text-white shadow-lg">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h3 class="text-xl font-bold">Buka Akses Semua Template Premium</h3>
                    <p class="mt-1 text-sm text-white/90">Tingkatkan paket Anda ke Pro / Business untuk menggunakan seluruh desain undangan eksklusif tanpa batas.</p>
                </div>
                <a
                    href="{{ route('subscription.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 bg-white text-rose-600 font-bold text-sm rounded-xl hover:bg-rose-50 transition shadow-sm shrink-0"
                >
                    Upgrade Paket Sekarang
                </a>
            </div>
        </div>
    @endif
</div>
@endsection
