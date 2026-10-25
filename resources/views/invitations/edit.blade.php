@extends('layouts.dashboard')

@section('title', 'Edit Undangan')

@section('actions')
    <div class="flex items-center gap-2.5">
        <!-- Toggle Publish Button -->
        <form action="{{ route('invitations.togglePublish', $invitation) }}" method="POST">
            @csrf
            @if($invitation->is_published)
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-amber-100 transition">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <span>Unpublish (Jadikan Draft)</span>
                </button>
            @else
                <button type="submit" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-600 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-emerald-700 transition shadow-sm">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Publish Sekarang</span>
                </button>
            @endif
        </form>

        <!-- Preview -->
        <a href="{{ route('invitations.show', $invitation) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-100 text-slate-700 text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-200 transition">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>Preview</span>
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-3xl mx-auto" x-data="{ deleteModalOpen: false, advancedSettingsOpen: false }">
    
    <!-- Meta Info & Live URL Banner -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3 text-xs sm:text-sm text-slate-500">
        <div>
            <span>Terakhir diupdate: {{ $invitation->updated_at->diffForHumans() }}</span>
        </div>
        <div>
            @if($invitation->is_published)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-50 text-emerald-700 font-semibold rounded-full border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Status: Published (Live)
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-slate-100 text-slate-600 font-semibold rounded-full border border-slate-200">
                    <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                    Status: Draft
                </span>
            @endif
        </div>
    </div>

    @if($invitation->is_published)
        <div class="mb-6 p-4 rounded-2xl bg-emerald-50 border border-emerald-200/80" x-data="{ copied: false }">
            <p class="text-xs font-bold text-emerald-800 uppercase tracking-wider mb-2">Link Undangan Publik Anda:</p>
            <div class="flex items-center gap-2">
                <input
                    type="text"
                    value="{{ $invitation->public_url }}"
                    readonly
                    class="flex-1 px-3.5 py-2 text-xs sm:text-sm bg-white border border-emerald-200 rounded-xl text-slate-700"
                >
                <button
                    type="button"
                    @click="navigator.clipboard.writeText('{{ $invitation->public_url }}'); copied = true; setTimeout(() => copied = false, 2000)"
                    class="px-4 py-2 text-xs sm:text-sm font-semibold rounded-xl text-white transition shadow-sm"
                    :class="copied ? 'bg-emerald-700' : 'bg-emerald-600 hover:bg-emerald-700'"
                >
                    <span x-text="copied ? 'Tersalin!' : 'Salin Link'"></span>
                </button>
                <a 
                    href="{{ $invitation->public_url }}" 
                    target="_blank" 
                    class="px-4 py-2 text-xs sm:text-sm font-semibold bg-white border border-emerald-300 text-emerald-700 rounded-xl hover:bg-emerald-100/50 transition"
                >
                    Buka
                </a>
            </div>
        </div>
    @endif

    <form action="{{ route('invitations.update', $invitation) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        @php
            $cfg = $invitation->theme_config ?? [];
            $bankAcc = $cfg['bank_accounts'][0] ?? [];
        @endphp

        <!-- Section 1: Informasi Pengantin -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">1</span>
                <span>Informasi Pengantin</span>
            </h3>

            <div class="grid sm:grid-cols-2 gap-4 pt-2">
                <div>
                    <label for="groom_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap Mempelai Pria <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="groom_name"
                        name="groom_name"
                        value="{{ old('groom_name', $invitation->groom_name) }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('groom_name') border-rose-500 @enderror"
                    >
                    @error('groom_name')
                        <p class="mt-1 text-xs text-rose-500 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="bride_name" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nama Lengkap Mempelai Wanita <span class="text-rose-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="bride_name"
                        name="bride_name"
                        value="{{ old('bride_name', $invitation->bride_name) }}"
                        required
                        class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('bride_name') border-rose-500 @enderror"
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
                <span>Detail &amp; Lokasi Acara</span>
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
                        value="{{ old('event_date', $invitation->event_date?->format('Y-m-d')) }}"
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
                        value="{{ old('event_time', $invitation->event_time?->format('H:i') ?? '09:00') }}"
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
                    value="{{ old('venue_name', $invitation->venue_name) }}"
                    required
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('venue_name') border-rose-500 @enderror"
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
                >{{ old('venue_address', $invitation->venue_address) }}</textarea>
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
                    value="{{ old('google_maps_url', $invitation->google_maps_url) }}"
                    placeholder="https://maps.google.com/..."
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('google_maps_url') border-rose-500 @enderror"
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

            @php
                $currentMsg = old('message', $invitation->message ?? '');
            @endphp
            <div x-data="{ charCount: {{ strlen($currentMsg) }} }">
                <label for="message" class="block text-xs font-semibold text-slate-700 uppercase tracking-wider mb-1.5">
                    Pesan / Doa dari Pengantin <span class="text-slate-400 font-normal lowercase">(opsional)</span>
                </label>
                <textarea
                    id="message"
                    name="message"
                    rows="4"
                    maxlength="1000"
                    @input="charCount = $event.target.value.length"
                    class="w-full px-4 py-2.5 text-sm bg-slate-50/50 border border-slate-200 rounded-xl focus:bg-white focus:ring-2 focus:ring-rose-500 focus:border-rose-500"
                >{{ $currentMsg }}</textarea>
                <p class="mt-1 text-right text-xs text-slate-400">
                    <span x-text="charCount"></span>/1000 karakter
                </p>
            </div>
        </div>

        <!-- Section 4: Galeri Foto -->
        @php
            $maxPhotos = auth()->user()->getMaxGalleryPhotos();
            $currentPhotosCount = $invitation->galleries->count();
            $isQuotaFull = $currentPhotosCount >= $maxPhotos;
            $remainingPhotos = max(0, $maxPhotos - $currentPhotosCount);
        @endphp
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-5">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">4</span>
                    <span>Galeri Foto Pernikahan</span>
                </h3>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $isQuotaFull ? 'bg-amber-100 text-amber-800' : 'bg-rose-50 text-rose-700' }}">
                        Anda telah mengunggah {{ $currentPhotosCount }} dari {{ $maxPhotos >= 50 ? 'Unlimited (50)' : $maxPhotos }} foto
                    </span>
                    @if($isQuotaFull && auth()->user()->subscription_plan !== 'business')
                        <a href="{{ route('subscription.index') }}" class="px-3 py-1 bg-gradient-to-r from-rose-500 to-pink-500 text-white text-xs font-semibold rounded-full hover:from-rose-600 hover:to-pink-600 transition shadow-sm">
                            Upgrade Paket
                        </a>
                    @endif
                </div>
            </div>

            <!-- Existing Galleries Grid -->
            @if($currentPhotosCount > 0)
                <div class="space-y-2">
                    <p class="text-xs font-bold text-slate-700 uppercase tracking-wider">Foto yang Sudah Diunggah:</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 pt-1">
                        @foreach($invitation->galleries as $gallery)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 bg-slate-100 aspect-square shadow-sm">
                                <img src="{{ $gallery->image_url }}" alt="{{ $gallery->caption ?? 'Gallery' }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                <div class="absolute inset-0 bg-slate-900/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center p-2">
                                    <button
                                        type="submit"
                                        form="delete-gallery-{{ $gallery->id }}"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus foto ini?')"
                                        class="px-3 py-1.5 bg-rose-600 text-white rounded-xl hover:bg-rose-700 transition shadow-lg flex items-center gap-1.5 text-xs font-bold"
                                        title="Hapus foto"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                        <span>Hapus</span>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Upload Area -->
            @if($isQuotaFull)
                <div class="p-5 bg-amber-50/80 rounded-2xl border border-amber-200 text-center space-y-2">
                    <p class="text-sm font-bold text-amber-900">Batas Kuota Foto Terpenuhi</p>
                    <p class="text-xs text-amber-700">
                        Anda telah mencapai batas maksimal {{ $maxPhotos }} foto untuk paket Anda saat ini.
                    </p>
                    <div class="pt-2">
                        <a href="{{ route('subscription.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-bold text-xs rounded-xl hover:from-rose-600 hover:to-pink-600 transition shadow-sm">
                            Upgrade Paket untuk Tambah Kuota Foto &rarr;
                        </a>
                    </div>
                </div>
            @else
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
                                    Bisa memilih beberapa foto sekaligus. Sisa kuota: <strong>{{ $remainingPhotos }}</strong> foto (Maks. 5MB per file).
                                </p>
                            </div>
                        </div>
                    </div>

                    <div x-show="filesCount > 0" x-cloak class="p-3 bg-emerald-50 rounded-xl border border-emerald-200 flex items-center justify-between text-xs text-emerald-800 font-medium">
                        <span x-text="filesCount + ' foto baru dipilih dan akan diunggah saat Anda menyimpan perubahan.'"></span>
                        <button type="button" @click="$refs.fileInput.value = ''; filesCount = 0" class="text-rose-600 font-bold hover:underline">
                            Batal Pilih
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <!-- Section 5: Love Story (Perjalanan Cinta) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">5</span>
                <span>Kisah Cinta (Love Story)</span>
            </h3>

            @if(auth()->user()->hasFeature('love_story'))
                @php
                    $initialStories = old('love_stories', $invitation->love_stories ?? [
                        ['date' => '2020', 'title' => 'Pertemuan Pertama', 'story' => ''],
                        ['date' => '2023', 'title' => 'Lamaran', 'story' => '']
                    ]);
                    if (empty($initialStories)) {
                        $initialStories = [['date' => '', 'title' => '', 'story' => '']];
                    }
                @endphp
                <div 
                    x-data="{
                        stories: {{ json_encode($initialStories) }},
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
                        Ceritakan momen-momen berharga perjalanan cinta Anda (mulai dari pertama bertemu, pacaran, hingga lamaran) untuk ditampilkan dalam bentuk timeline di undangan.
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

        <!-- Section 6: Kustomisasi Lanjutan Template (Orang Tua, Resepsi, Amplop, Musik, Live Stream) -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between cursor-pointer" @click="advancedSettingsOpen = !advancedSettingsOpen">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">6</span>
                    <span>Pengaturan Lanjutan &amp; Kustomisasi</span>
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
                                <input type="text" name="theme_config[groom_nickname]" value="{{ old('theme_config.groom_nickname', $cfg['groom_nickname'] ?? '') }}" placeholder="Contoh: Wahyu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Anak (Urutan)</label>
                                <input type="text" name="theme_config[groom_order]" value="{{ old('theme_config.groom_order', $cfg['groom_order'] ?? 'Putra pertama dari') }}" placeholder="Putra pertama dari" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ayah</label>
                                <input type="text" name="theme_config[groom_father]" value="{{ old('theme_config.groom_father', $cfg['groom_father'] ?? '') }}" placeholder="Bpk. Nama Ayah" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ibu</label>
                                <input type="text" name="theme_config[groom_mother]" value="{{ old('theme_config.groom_mother', $cfg['groom_mother'] ?? '') }}" placeholder="Ibu Nama Ibu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                        </div>

                        <!-- Wanita -->
                        <div class="p-4 bg-slate-50/60 rounded-2xl border border-slate-200/60 space-y-3">
                            <span class="text-xs font-bold text-rose-600 uppercase">Mempelai Wanita</span>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Panggilan</label>
                                <input type="text" name="theme_config[bride_nickname]" value="{{ old('theme_config.bride_nickname', $cfg['bride_nickname'] ?? '') }}" placeholder="Contoh: Riski" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Status Anak (Urutan)</label>
                                <input type="text" name="theme_config[bride_order]" value="{{ old('theme_config.bride_order', $cfg['bride_order'] ?? 'Putri kedua dari') }}" placeholder="Putri kedua dari" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ayah</label>
                                <input type="text" name="theme_config[bride_father]" value="{{ old('theme_config.bride_father', $cfg['bride_father'] ?? '') }}" placeholder="Bpk. Nama Ayah" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Ibu</label>
                                <input type="text" name="theme_config[bride_mother]" value="{{ old('theme_config.bride_mother', $cfg['bride_mother'] ?? '') }}" placeholder="Ibu Nama Ibu" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
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
                            <input type="text" name="theme_config[akad_time]" value="{{ old('theme_config.akad_time', $cfg['akad_time'] ?? '08:00 WIB - Selesai') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Waktu Resepsi</label>
                            <input type="text" name="theme_config[resepsi_time]" value="{{ old('theme_config.resepsi_time', $cfg['resepsi_time'] ?? '11:00 WIB - Selesai') }}" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Petunjuk Busana (Dresscode)</label>
                            <input type="text" name="dresscode" value="{{ old('dresscode', $invitation->dresscode ?? $cfg['dress_code_desc'] ?? 'Busana batik atau formal pastel.') }}" placeholder="Contoh: Batik Modern" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Sub 3: Amplop Digital -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Amplop Digital (Love Gift)</h4>
                    <div class="grid sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nama Bank / E-Wallet</label>
                            <input type="text" name="theme_config[bank_name]" value="{{ old('theme_config.bank_name', $bankAcc['bank_name'] ?? 'Bank Central Asia (BCA)') }}" placeholder="BCA / Mandiri / BRI / GoPay" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Nomor Rekening</label>
                            <input type="text" name="theme_config[bank_account]" value="{{ old('theme_config.bank_account', $bankAcc['account_number'] ?? '') }}" placeholder="Contoh: 1234567890" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Atas Nama Rekening</label>
                            <input type="text" name="theme_config[bank_owner]" value="{{ old('theme_config.bank_owner', $bankAcc['account_holder'] ?? '') }}" placeholder="Atas Nama Pemilik" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        </div>
                    </div>
                </div>

                <!-- Sub 4: Musik & Audio -->
                <div class="space-y-3">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Pengaturan Musik &amp; Media</h4>
                    <div>
                        <label class="block text-xs font-semibold text-slate-600 mb-1">URL File Audio Musik (MP3)</label>
                        <input type="text" name="music_url" value="{{ old('music_url', $invitation->music_url ?? $cfg['audio_url'] ?? '') }}" placeholder="https://assets.mixkit.co/music/preview/mixkit-romantic-wedding-28.mp3" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                        <small class="text-slate-400 text-[11px]">Masukkan direct link audio MP3 romantis untuk diputar otomatis di undangan.</small>
                    </div>
                </div>

                <!-- Sub 5: Live Streaming & Font -->
                <div class="space-y-4">
                    <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Live Streaming &amp; Kustomisasi Desain</h4>
                    <div class="grid sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Link Live Streaming (YouTube / IG)</label>
                            <input type="url" name="live_streaming_url" value="{{ old('live_streaming_url', $invitation->live_streaming_url) }}" placeholder="https://youtube.com/live/..." class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                            <small class="text-slate-400 text-[11px]">Tamu dapat menonton siaran langsung akad / resepsi.</small>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 mb-1">Pilihan Font / Tipografi</label>
                            <select name="custom_font" class="w-full px-3 py-2 text-xs bg-white border border-slate-200 rounded-lg">
                                <option value="" {{ empty($invitation->custom_font) ? 'selected' : '' }}>Default Template (Playfair Display)</option>
                                <option value="Playfair Display" {{ old('custom_font', $invitation->custom_font) == 'Playfair Display' ? 'selected' : '' }}>Playfair Display (Klasik & Elegan)</option>
                                <option value="Cinzel" {{ old('custom_font', $invitation->custom_font) == 'Cinzel' ? 'selected' : '' }}>Cinzel (Royal & Mewah)</option>
                                <option value="Great Vibes" {{ old('custom_font', $invitation->custom_font) == 'Great Vibes' ? 'selected' : '' }}>Great Vibes (Kaligrafi Romantis)</option>
                                <option value="Montserrat" {{ old('custom_font', $invitation->custom_font) == 'Montserrat' ? 'selected' : '' }}>Montserrat (Modern Elegan)</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 7: Pilih Tema Template -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-sm border border-slate-100 space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                    <span class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-xs font-bold">7</span>
                    <span>Pilih Tema Template</span>
                </h3>
            </div>

            @php
                $selectedTpl = old('template_id', $invitation->template_id);
            @endphp

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
                                {{ $selectedTpl == $template->id ? 'checked' : '' }}
                            >
                            <div class="border-2 border-slate-200 rounded-2xl overflow-hidden transition peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-200 group-hover:border-slate-300">
                                <div class="aspect-[3/4] bg-slate-100 overflow-hidden">
                                    <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-3 bg-white">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $template->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full">Gratis</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Premium Templates -->
            <div class="pt-2">
                <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Template Premium</h4>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                    @foreach($premiumTemplates as $template)
                        <label class="relative {{ $canUsePremium ? 'cursor-pointer' : 'cursor-not-allowed' }} group">
                            <input
                                type="radio"
                                name="template_id"
                                value="{{ $template->id }}"
                                class="peer sr-only"
                                {{ $selectedTpl == $template->id ? 'checked' : '' }}
                                {{ ! $canUsePremium ? 'disabled' : '' }}
                            >
                            <div class="border-2 border-slate-200 rounded-2xl overflow-hidden transition {{ $canUsePremium ? 'peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-200 group-hover:border-slate-300' : 'opacity-60' }}">
                                <div class="aspect-[3/4] bg-slate-100 relative overflow-hidden">
                                    <img src="{{ $template->thumbnail }}" alt="{{ $template->name }}" class="w-full h-full object-cover">
                                    @if(!$canUsePremium)
                                        <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-[1px] flex items-center justify-center text-white">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="p-3 bg-white">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $template->name }}</p>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full">Premium</span>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between pt-2">
            <button
                type="button"
                @click="deleteModalOpen = true"
                class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-rose-600 hover:text-rose-700 transition"
            >
                Hapus Undangan Ini
            </button>

            <div class="flex items-center gap-3">
                <a href="{{ route('invitations.index') }}" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-800 transition">
                    Batal
                </a>
                <button 
                    type="submit" 
                    class="px-6 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold text-sm rounded-xl shadow-sm hover:from-rose-600 hover:to-pink-600 transition"
                >
                    Simpan Perubahan
                </button>
            </div>
        </div>
    </form>

    <!-- Hidden Delete Forms for Gallery Photos -->
    @foreach($invitation->galleries as $gallery)
        <form id="delete-gallery-{{ $gallery->id }}" action="{{ route('invitations.galleries.destroy', [$invitation, $gallery]) }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

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
                <h3 class="text-lg font-bold text-slate-900">Hapus Undangan?</h3>
                <p class="mt-2 text-sm text-slate-600">
                    Undangan <span class="font-semibold text-slate-900">{{ $invitation->groom_name }} &amp; {{ $invitation->bride_name }}</span> beserta seluruh data RSVP tamu akan dihapus.
                </p>

                <div class="mt-6 flex items-center justify-center gap-3">
                    <button
                        type="button"
                        @click="deleteModalOpen = false"
                        class="px-5 py-2.5 text-sm font-semibold text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition"
                    >
                        Batal
                    </button>
                    <form action="{{ route('invitations.destroy', $invitation) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button
                            type="submit"
                            class="px-5 py-2.5 text-sm font-semibold text-white bg-rose-600 rounded-xl hover:bg-rose-700 transition shadow-sm"
                        >
                            Ya, Hapus Sekarang
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
