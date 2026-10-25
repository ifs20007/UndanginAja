@extends('layouts.dashboard')

@section('title', 'Langganan & Paket')

@section('content')
<div class="max-w-5xl mx-auto space-y-10">
    
    <!-- Status Notification from Callback -->
    @if(request('status') === 'success')
        <div class="p-4 bg-emerald-50 border border-emerald-200 rounded-2xl flex items-center gap-3 text-emerald-800 text-sm">
            <svg class="w-6 h-6 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p><strong class="font-bold">Pembayaran Berhasil!</strong> Paket langganan Anda telah berhasil diaktifkan. Nikmati fitur premium UndanginAja!</p>
        </div>
    @endif

    @if(request('status') === 'pending')
        <div class="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex items-center gap-3 text-amber-800 text-sm">
            <svg class="w-6 h-6 text-amber-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
            </svg>
            <p><strong class="font-bold">Pembayaran Sedang Diproses:</strong> Menunggu konfirmasi pembayaran dari sistem gateway Midtrans.</p>
        </div>
    @endif

    <!-- Header Section -->
    <div class="text-center max-w-xl mx-auto">
        <h2 class="text-3xl font-bold text-slate-900">Pilih Paket Langganan Terbaik</h2>
        <p class="mt-2 text-sm text-slate-600">Dapatkan kuota undangan lebih banyak, akses seluruh template eksklusif, dan fitur kustomisasi tanpa batas.</p>
    </div>

    <!-- Active Subscription Card -->
    @if($hasActiveSubscription && $currentPlan)
        <div class="p-6 bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-3xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-md">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div>
                    <h4 class="font-bold text-emerald-950 text-base">Langganan Aktif: Paket {{ $currentPlan->name }}</h4>
                    <p class="text-xs text-emerald-800 mt-0.5">
                        Berlaku hingga <span class="font-bold">{{ auth()->user()->subscription_expires_at?->format('d F Y') }}</span>
                        ({{ auth()->user()->remaining_invitations }} kuota undangan tersisa).
                    </p>
                </div>
            </div>
        </div>
    @endif

    <!-- Pricing Cards Grid -->
    <div class="grid md:grid-cols-3 gap-6 items-stretch">
        @foreach($plans as $plan)
            @php
                $isCurrent = $currentPlan && $currentPlan->id === $plan->id;
                $isPopular = $plan->is_popular;
            @endphp

            <div class="relative flex flex-col {{ $isPopular ? 'md:-translate-y-2' : '' }}">
                @if($isPopular)
                    <div class="absolute -top-3.5 left-1/2 -translate-x-1/2 z-10">
                        <span class="px-3.5 py-1 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-[11px] font-bold rounded-full shadow-md uppercase tracking-wider">
                            Paling Populer
                        </span>
                    </div>
                @endif

                <div class="h-full bg-white rounded-3xl shadow-sm border-2 {{ $isPopular ? 'border-rose-500 shadow-xl' : 'border-slate-100' }} p-6 sm:p-8 flex flex-col justify-between">
                    <div>
                        <!-- Plan Name & Price -->
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

                        <!-- Features -->
                        <div class="py-6">
                            <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3.5">Fitur Termasuk:</p>
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

                    <!-- CTA Button -->
                    <div class="pt-4">
                        @if($isCurrent)
                            <button disabled class="w-full py-3 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed">
                                Paket Saat Ini
                            </button>
                        @elseif($plan->price === 0)
                            <button disabled class="w-full py-3 bg-slate-100 text-slate-400 font-bold text-xs rounded-xl cursor-not-allowed">
                                Paket Standar
                            </button>
                        @else
                            <a
                                href="{{ route('subscription.checkout', $plan) }}"
                                class="block w-full py-3 text-center text-xs font-bold rounded-xl text-white transition shadow-sm {{ $isPopular ? 'bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600' : 'bg-slate-900 hover:bg-slate-800' }}"
                            >
                                Pilih & Upgrade {{ $plan->name }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- FAQ Section -->
    <div class="pt-8 border-t border-slate-200">
        <h3 class="text-xl font-bold text-slate-900 text-center mb-6">Pertanyaan Seputar Langganan</h3>
        <div class="grid sm:grid-cols-2 gap-4 max-w-3xl mx-auto">
            <div class="p-5 bg-white rounded-2xl border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm">Metode pembayaran apa saja yang didukung?</h4>
                <p class="text-xs text-slate-500 mt-1">Kami mendukung QRIS (GoPay, OVO, ShopeePay, DANA), Transfer Bank Virtual Account (BCA, Mandiri, BNI, BRI), dan Kartu Kredit melalui Midtrans.</p>
            </div>
            <div class="p-5 bg-white rounded-2xl border border-slate-100">
                <h4 class="font-bold text-slate-900 text-sm">Berapa lama status paket aktif setelah bayar?</h4>
                <p class="text-xs text-slate-500 mt-1">Status paket langganan Anda aktif secara instan otomatis begitu proses pembayaran di Midtrans terverifikasi sukses.</p>
            </div>
        </div>
    </div>
</div>
@endsection
