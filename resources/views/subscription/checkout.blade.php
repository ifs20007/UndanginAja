@extends('layouts.dashboard')

@section('title', 'Checkout Pembayaran')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <!-- Back Link -->
    <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
        &larr; Kembali ke Pilihan Paket
    </a>

    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 sm:p-8 space-y-6">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Ringkasan Pembayaran</h2>
            <p class="text-xs text-slate-500 mt-1">Lakukan pembayaran dengan aman menggunakan payment gateway Midtrans.</p>
        </div>

        <!-- Order Summary Details -->
        <div class="p-5 bg-slate-50 rounded-2xl border border-slate-100 space-y-3 text-sm">
            <div class="flex justify-between items-center text-slate-600">
                <span>Paket Langganan</span>
                <span class="font-bold text-slate-900">UndanginAja {{ $plan->name }}</span>
            </div>
            <div class="flex justify-between items-center text-slate-600">
                <span>Masa Aktif</span>
                <span class="font-medium text-slate-900">30 Hari (1 Event/Bulan)</span>
            </div>
            <div class="flex justify-between items-center text-slate-600">
                <span>Harga Paket</span>
                <span class="font-medium text-slate-900">{{ $plan->formatted_price }}</span>
            </div>
            <div class="pt-3 border-t border-slate-200 flex justify-between items-center">
                <span class="font-bold text-slate-900 text-base">Total Tagihan</span>
                <span class="font-extrabold text-xl text-rose-600">{{ $plan->formatted_price }}</span>
            </div>
        </div>

        <!-- Buyer Info -->
        <div class="space-y-3">
            <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Informasi Akun Pembeli</h3>
            <div class="grid sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-slate-400">Nama</p>
                    <p class="font-bold text-slate-800 mt-0.5">{{ $user->name }}</p>
                </div>
                <div class="p-3 bg-slate-50 rounded-xl border border-slate-100">
                    <p class="text-slate-400">Email</p>
                    <p class="font-bold text-slate-800 mt-0.5 truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Payment Button & Security Note -->
        <div class="pt-4 space-y-3">
            <button
                type="button"
                id="pay-button"
                class="w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-sm rounded-2xl shadow-md hover:from-rose-600 hover:to-pink-600 transition flex items-center justify-center gap-2 disabled:opacity-50"
            >
                <span id="button-text">Bayar Sekarang &bull; {{ $plan->formatted_price }}</span>
                <svg id="button-spinner" class="hidden w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </button>

            <p class="text-center text-xs text-slate-400 flex items-center justify-center gap-1.5">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                </svg>
                <span>Pembayaran aman & terenkripsi oleh Midtrans Payment Gateway</span>
            </p>
        </div>
    </div>
</div>

<!-- Midtrans Snap JS -->
@php
    $snapUrl = config('midtrans.is_production')
        ? 'https://app.midtrans.com/snap/snap.js'
        : 'https://app.sandbox.midtrans.com/snap/snap.js';
@endphp
<script src="{{ $snapUrl }}" data-client-key="{{ $clientKey }}"></script>

<script>
    const payBtn = document.getElementById('pay-button');
    const btnText = document.getElementById('button-text');
    const btnSpinner = document.getElementById('button-spinner');

    payBtn.addEventListener('click', async function() {
        payBtn.disabled = true;
        btnText.textContent = 'Menghubungkan ke Midtrans...';
        btnSpinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("transactions.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    plan_id: {{ $plan->id }}
                })
            });

            const data = await response.json();

            if (data.error) {
                alert(data.error);
                resetButton();
                return;
            }

            if (window.snap) {
                window.snap.pay(data.snap_token, {
                    onSuccess: function(result) {
                        window.location.href = '{{ route("subscription.index") }}?status=success';
                    },
                    onPending: function(result) {
                        window.location.href = '{{ route("subscription.index") }}?status=pending';
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal atau dibatalkan.');
                        resetButton();
                    },
                    onClose: function() {
                        resetButton();
                    }
                });
            } else {
                alert('Midtrans Snap SDK gagal dimuat. Periksa koneksi internet atau kunci API Midtrans Anda.');
                resetButton();
            }

        } catch (error) {
            console.error('Checkout error:', error);
            alert('Terjadi kesalahan saat memproses pesanan.');
            resetButton();
        }
    });

    function resetButton() {
        payBtn.disabled = false;
        btnText.textContent = 'Bayar Sekarang \u2022 {{ $plan->formatted_price }}';
        btnSpinner.classList.add('hidden');
    }
</script>
@endsection
