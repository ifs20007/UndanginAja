@extends('layouts.dashboard')

@section('title', 'Buat Undangan Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('invitations.index') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-slate-500 hover:text-slate-800 transition mb-2">
            &larr; Kembali ke Undangan Saya
        </a>
        <h2 class="text-2xl font-bold text-slate-900">Buat Undangan Pernikahan</h2>
        <p class="text-sm text-slate-600 mt-1">Isi formulir berikut dengan informasi lengkap untuk kedua mempelai dan detail acara.</p>
    </div>

    <form action="{{ route('invitations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ advancedSettingsOpen: false }">
        @csrf

        <!-- Section 1: Informasi Pengantin -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">1</span>
                <span>Informasi Pengantin</span>
            </h3>

            <div class="grid sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="groom_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Mempelai Pria <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="groom_name"
                        name="groom_name"
                        value="{{ old('groom_name') }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_name') border-rose-500 @enderror"
                        placeholder="Contoh: Ahmad Fauzi"
                    >
                    @error('groom_name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bride_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Mempelai Wanita <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="bride_name"
                        name="bride_name"
                        value="{{ old('bride_name') }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_name') border-rose-500 @enderror"
                        placeholder="Contoh: Siti Nurhaliza"
                    >
                    @error('bride_name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Section 2: Detail Acara -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">2</span>
                <span>Detail & Lokasi Acara</span>
            </h3>

            <div class="grid sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="event_date" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Tanggal Acara <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="date"
                        id="event_date"
                        name="event_date"
                        value="{{ old('event_date') }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('event_date') border-rose-500 @enderror"
                    >
                    @error('event_date')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="event_time" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Waktu Acara <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="time"
                        id="event_time"
                        name="event_time"
                        value="{{ old('event_time', '09:00') }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('event_time') border-rose-500 @enderror"
                    >
                    @error('event_time')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="venue_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Nama Tempat / Gedung <span class="text-rose-500">*</span>
                </label>
                <input
                    type="text"
                    id="venue_name"
                    name="venue_name"
                    value="{{ old('venue_name') }}"
                    required
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('venue_name') border-rose-500 @enderror"
                    placeholder="Contoh: Gedung Serbaguna Balai Kartini"
                >
                @error('venue_name')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="venue_address" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Alamat Lengkap <span class="text-rose-500">*</span>
                </label>
                <textarea
                    id="venue_address"
                    name="venue_address"
                    rows="3"
                    required
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('venue_address') border-rose-500 @enderror"
                    placeholder="Contoh: Jl. Gatot Subroto Kav. 37, Kuningan Barat, Jakarta Selatan"
                >{{ old('venue_address') }}</textarea>
                @error('venue_address')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="google_maps_url" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Link Google Maps <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                </label>
                <input
                    type="url"
                    id="google_maps_url"
                    name="google_maps_url"
                    value="{{ old('google_maps_url') }}"
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('google_maps_url') border-rose-500 @enderror"
                    placeholder="https://maps.app.goo.gl/..."
                >
                @error('google_maps_url')
                    <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Section 3: Pesan untuk Tamu -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">3</span>
                <span>Pesan untuk Tamu</span>
            </h3>

            <div x-data="{ charCount: {{ strlen(old('message', '')) }} }">
                <label for="message" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pesan / Doa dari Pengantin <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                </label>
                <textarea
                    id="message"
                    name="message"
                    rows="4"
                    maxlength="1000"
                    x-on:input="charCount = $event.target.value.length"
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('message') border-rose-500 @enderror"
                    placeholder="Tuliskan ucapan sambutan, ungkapan rasa syukur, atau pesan khusus untuk tamu undangan..."
                >{{ old('message') }}</textarea>
                <div class="flex justify-between items-center mt-1.5 text-xs text-slate-400">
                    <span>
                        @error('message')
                            <span class="text-rose-500 font-medium">{{ $message }}</span>
                        @enderror
                    </span>
                    <span><span x-text="charCount">0</span>/1000 karakter</span>
                </div>
            </div>
        </div>

        <!-- Section 4: Galeri Foto -->
        @php
            $maxPhotos = auth()->user()->getMaxGalleryPhotos();
        @endphp
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">4</span>
                    <span>Galeri Foto Pernikahan <span class="text-slate-400 font-normal text-xs lowercase">(opsional)</span></span>
                </h3>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700">
                    Batas Kuota: {{ $maxPhotos >= 50 ? 'Unlimited (50)' : $maxPhotos }} Foto
                </span>
            </div>

            <div 
                x-data="{ isDragging: false, filesCount: 0 }"
                class="space-y-3"
            >
                <div
                    @dragover.prevent="isDragging = true"
                    @dragleave.prevent="isDragging = false"
                    @drop.prevent="
                        isDragging = false; 
                        $refs.fileInput.files = $event.dataTransfer.files;
                        filesCount = $refs.fileInput.files.length;
                    "
                    :class="isDragging ? 'border-rose-500 bg-rose-50/40 ring-2 ring-rose-200' : 'border-slate-200 bg-slate-50/50 hover:bg-slate-50'"
                    class="border-2 border-dashed rounded-2xl p-6 text-center transition cursor-pointer relative"
                    @click="$refs.fileInput.click()"
                >
                    <input
                        type="file"
                        x-ref="fileInput"
                        name="gallery_photos[]"
                        multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
                        class="hidden"
                        @change="filesCount = $event.target.files.length"
                    >
                    <div class="space-y-2">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-xs sm:text-sm font-bold text-slate-800">
                                Tarik & letakkan foto di sini, atau <span class="text-rose-600 underline">klik untuk memilih foto</span>
                            </p>
                            <p class="text-[11px] text-slate-500 mt-1">
                                Anda dapat mengunggah hingga {{ $maxPhotos }} foto (JPG, PNG, WEBP maks. 5MB per file).
                            </p>
                        </div>
                    </div>
                </div>

                <div x-show="filesCount > 0" x-cloak class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs text-emerald-800 font-medium">
                    <span x-text="filesCount + ' foto dipilih dan akan diunggah bersama undangan baru.'"></span>
                    <button type="button" @click="$refs.fileInput.value = ''; filesCount = 0" class="text-rose-600 font-bold hover:underline">
                        Batal Pilih
                    </button>
                </div>
            </div>
        </div>

        <!-- Section 5: Kisah Cinta (Love Story) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">5</span>
                <span>Kisah Cinta (Love Story) <span class="text-slate-400 font-normal text-xs lowercase">(opsional)</span></span>
            </h3>

            @if(auth()->user()->hasFeature('love_story'))
                <div 
                    x-data="{
                        stories: {{ json_encode(old('love_stories', [
                            ['date' => '2020', 'title' => 'Pertemuan Pertama', 'story' => ''],
                            ['date' => '2023', 'title' => 'Lamaran', 'story' => '']
                        ])) }},
                        addStory() {
                            this.stories.push({ date: '', title: '', story: '' });
                        },
                        removeStory(index) {
                            if (this.stories.length > 1) {
                                this.stories.splice(index, 1);
                            } else {
                                this.stories = [{ date: '', title: '', story: '' }];
                            }
                        }
                    }" 
                    class="space-y-4 pt-1"
                >
                    <p class="text-xs text-slate-500">
                        Ceritakan lembaran perjalanan cinta Anda untuk ditampilkan dalam timeline vertikal interaktif pada halaman undangan.
                    </p>

                    <div class="space-y-4">
                        <template x-for="(story, index) in stories" :key="index">
                            <div class="p-4 bg-slate-50/70 rounded-2xl border border-slate-200/80 space-y-3 relative group">
                                <div class="flex items-center justify-between">
                                    <span class="text-xs font-bold text-rose-600 uppercase flex items-center gap-1.5">
                                        <span class="w-5 h-5 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center text-[10px]" x-text="index + 1"></span>
                                        <span>Momen Perjalanan</span>
                                    </span>
                                    <button 
                                        type="button" 
                                        @click="removeStory(index)"
                                        class="text-xs font-semibold text-rose-500 hover:text-rose-700 hover:underline flex items-center gap-1"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal / Tahun Momen</label>
                                        <input 
                                            type="text" 
                                            :name="'love_stories[' + index + '][date]'" 
                                            x-model="story.date" 
                                            placeholder="Contoh: 15 Juni 2021 / 2021" 
                                            class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-rose-500 focus:border-rose-500"
                                        >
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Judul Momen</label>
                                        <input 
                                            type="text" 
                                            :name="'love_stories[' + index + '][title]'" 
                                            x-model="story.title" 
                                            placeholder="Contoh: Pertama Kali Bertemu" 
                                            class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-rose-500 focus:border-rose-500"
                                        >
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Cerita Singkat</label>
                                    <textarea 
                                        :name="'love_stories[' + index + '][story]'" 
                                        x-model="story.story" 
                                        rows="2" 
                                        placeholder="Ceritakan bagaimana momen ini terjadi..." 
                                        class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg focus:ring-rose-500 focus:border-rose-500"
                                    ></textarea>
                                </div>
                            </div>
                        </template>
                    </div>

                    <button 
                        type="button" 
                        @click="addStory" 
                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 text-rose-600 font-bold text-xs rounded-xl hover:bg-rose-100 transition border border-rose-200"
                    >
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>Tambah Momen Cerita Baru</span>
                    </button>
                </div>
            @else
                <!-- Locked Paywall Banner for Non-Business -->
                <div class="p-6 bg-gradient-to-r from-slate-900 to-slate-800 text-white rounded-2xl relative overflow-hidden shadow-md">
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div class="flex items-start gap-3.5">
                            <div class="p-3 bg-white/10 rounded-2xl backdrop-blur text-amber-400 shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                            <div class="space-y-1">
                                <span class="inline-block px-2 py-0.5 text-[10px] font-bold bg-amber-400/20 text-amber-300 rounded-full uppercase tracking-wider">
                                    Fitur Eksklusif Business
                                </span>
                                <h4 class="text-sm font-bold text-white">Timeline Perjalanan Cinta (Love Story)</h4>
                                <p class="text-xs text-slate-300 max-w-md">
                                    Bagikan kisah romantis perjalanan cinta Anda kepada para tamu undangan melalui timeline interaktif elegan.
                                </p>
                            </div>
                        </div>

                        <a 
                            href="{{ route('subscription.index') }}" 
                            class="shrink-0 px-4 py-2.5 bg-gradient-to-r from-amber-400 to-amber-500 text-slate-900 font-bold text-xs rounded-xl hover:from-amber-300 hover:to-amber-400 transition shadow-sm"
                        >
                            Upgrade ke Business &rarr;
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Section 6: Pengaturan Lanjutan (Orang Tua, Resepsi, Amplop, Musik, Live Stream) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between cursor-pointer" @click="advancedSettingsOpen = !advancedSettingsOpen">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">6</span>
                    <span>Pengaturan Lanjutan &amp; Kustomisasi <span class="text-slate-400 font-normal text-xs lowercase">(opsional)</span></span>
                </h3>
                <span class="text-xs font-bold text-rose-600 flex items-center gap-1">
                    <span x-text="advancedSettingsOpen ? 'Tutup Pengaturan' : 'Buka Pengaturan'"></span>
                    <svg class="w-4 h-4 transition transform" :class="advancedSettingsOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </span>
            </div>

            <div x-show="advancedSettingsOpen" x-transition class="space-y-6 pt-2 border-t border-slate-100">
                <!-- Sub 1: Nama Panggilan & Orang Tua -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Detail Nama Panggilan &amp; Orang Tua</h4>
                    
                    <div class="grid sm:grid-cols-2 gap-4">
                        <!-- Pria -->
                        <div class="p-4 bg-slate-50/60 rounded-2xl border border-slate-200/60 space-y-3">
                            <span class="text-xs font-bold text-rose-600 uppercase">Mempelai Pria</span>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Panggilan</label>
                                <input type="text" name="theme_config[groom_nickname]" value="{{ old('theme_config.groom_nickname') }}" placeholder="Contoh: Wahyu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Anak (Urutan)</label>
                                <input type="text" name="theme_config[groom_order]" value="{{ old('theme_config.groom_order', 'Putra pertama dari') }}" placeholder="Putra pertama dari" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ayah</label>
                                <input type="text" name="theme_config[groom_father]" value="{{ old('theme_config.groom_father') }}" placeholder="Bpk. Nama Ayah" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ibu</label>
                                <input type="text" name="theme_config[groom_mother]" value="{{ old('theme_config.groom_mother') }}" placeholder="Ibu Nama Ibu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                        </div>

                        <!-- Wanita -->
                        <div class="p-4 bg-slate-50/60 rounded-2xl border border-slate-200/60 space-y-3">
                            <span class="text-xs font-bold text-rose-600 uppercase">Mempelai Wanita</span>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Panggilan</label>
                                <input type="text" name="theme_config[bride_nickname]" value="{{ old('theme_config.bride_nickname') }}" placeholder="Contoh: Riski" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Anak (Urutan)</label>
                                <input type="text" name="theme_config[bride_order]" value="{{ old('theme_config.bride_order', 'Putri kedua dari') }}" placeholder="Putri kedua dari" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ayah</label>
                                <input type="text" name="theme_config[bride_father]" value="{{ old('theme_config.bride_father') }}" placeholder="Bpk. Nama Ayah" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ibu</label>
                                <input type="text" name="theme_config[bride_mother]" value="{{ old('theme_config.bride_mother') }}" placeholder="Ibu Nama Ibu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sub 2: Akad & Resepsi & Dresscode -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Jadwal Sesi &amp; Dresscode</h4>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Akad Nikah</label>
                            <input type="text" name="theme_config[akad_time]" value="{{ old('theme_config.akad_time', '08:00 WIB - Selesai') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Resepsi</label>
                            <input type="text" name="theme_config[resepsi_time]" value="{{ old('theme_config.resepsi_time', '11:00 WIB - Selesai') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Petunjuk Busana (Dresscode)</label>
                            <input type="text" name="dresscode" value="{{ old('dresscode', 'Busana batik atau formal pastel.') }}" placeholder="Contoh: Batik Modern" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Sub 3: Amplop Digital -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Amplop Digital (Love Gift)</h4>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Bank / E-Wallet</label>
                            <input type="text" name="theme_config[bank_name]" value="{{ old('theme_config.bank_name', 'Bank Central Asia (BCA)') }}" placeholder="BCA / Mandiri / BRI / GoPay" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Rekening</label>
                            <input type="text" name="theme_config[bank_account]" value="{{ old('theme_config.bank_account') }}" placeholder="Contoh: 1234567890" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Atas Nama Rekening</label>
                            <input type="text" name="theme_config[bank_owner]" value="{{ old('theme_config.bank_owner') }}" placeholder="Atas Nama Pemilik" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Sub 4: Musik & Audio -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pengaturan Musik &amp; Media</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">URL File Audio Musik (MP3)</label>
                        <input type="text" name="music_url" value="{{ old('music_url') }}" placeholder="https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-28.mp3" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        <small class="text-slate-400 text-[11px]">Direct link audio MP3 romantis untuk diputar otomatis di undangan.</small>
                    </div>
                </div>

                <!-- Sub 5: Live Streaming & Font -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Live Streaming &amp; Kustomisasi Desain</h4>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Link Live Streaming (YouTube / IG)</label>
                            <input type="url" name="live_streaming_url" value="{{ old('live_streaming_url') }}" placeholder="https://youtube.com/live/..." class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Pilihan Font / Tipografi</label>
                            <select name="custom_font" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                                <option value="">Default Template (Playfair Display)</option>
                                <option value="Playfair Display" {{ old('custom_font') == 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Klasik & Elegan)</option>
                                <option value="Cinzel" {{ old('custom_font') == 'Cinzel' ? 'selected' : '' }}>Cinzel (Royal & Mewah)</option>
                                <option value="Great Vibes" {{ old('custom_font') == 'Great Vibes' ? 'selected' : '' }}>Great Vibes (Kaligrafi Romantis)</option>
                                <option value="Montserrat" {{ old('custom_font') == 'Montserrat' ? 'selected' : '' }}>Montserrat (Modern Elegan)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 7: Pilih Template -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
            <div>
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">7</span>
                    <span>Pilih Tema Template</span>
                </h3>
                <p class="text-xs text-slate-500 mt-1">Pilih desain tema undangan yang Anda inginkan (dapat diganti sewaktu-waktu).</p>
            </div>

            <!-- Free Templates -->
            <div>
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Template Gratis</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($freeTemplates as $template)
                        <label class="relative cursor-pointer group">
                            <input
                                type="radio"
                                name="template_id"
                                value="{{ $template->id }}"
                                class="peer sr-only"
                                {{ old('template_id') == $template->id ? 'checked' : '' }}
                            >
                            <div class="border-2 border-slate-200 rounded-2xl overflow-hidden transition peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-200 group-hover:border-slate-300">
                                <div class="aspect-[3/4] bg-slate-100 overflow-hidden">
                                    <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-3 bg-white">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $template->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full">
                                        Gratis
                                    </span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Premium Templates -->
            <div class="pt-2">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Template Premium</h4>
                    @if(!$canUsePremium)
                        <a href="{{ route('subscription.index') }}" class="text-xs font-semibold text-rose-600 hover:underline">
                            Upgrade ke Pro untuk Akses &rarr;
                        </a>
                    @endif
                </div>

                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($premiumTemplates as $template)
                        <label class="relative {{ $canUsePremium ? 'cursor-pointer' : 'cursor-not-allowed' }} group">
                            <input
                                type="radio"
                                name="template_id"
                                value="{{ $template->id }}"
                                class="peer sr-only"
                                {{ old('template_id') == $template->id ? 'checked' : '' }}
                                {{ ! $canUsePremium ? 'disabled' : '' }}
                            >
                            <div class="border-2 border-slate-200 rounded-2xl overflow-hidden transition {{ $canUsePremium ? 'peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-200 group-hover:border-slate-300' : 'opacity-60' }}">
                                <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden">
                                    <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                    @if(!$canUsePremium)
                                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex items-center justify-center">
                                            <div class="p-2 rounded-full bg-white/20 backdrop-blur text-white">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 bg-white">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $template->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">
                                        Premium
                                    </span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
            <a href="{{ route('invitations.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">
                Batal
            </a>
            <button 
                type="submit" 
                class="px-6 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold text-sm rounded-xl shadow-sm hover:from-rose-600 hover:to-pink-600 transition"
            >
                Simpan & Buat Undangan
            </button>
        </div>
    </form>
</div>
@endsection
