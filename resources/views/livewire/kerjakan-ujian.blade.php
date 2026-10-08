@php
    $totalSoal = $this->soals->count();
    $jumlahTerjawab = $terjawab->count();
    $belumDijawab = $totalSoal - $jumlahTerjawab;
    // Indeks soal yang belum dijawab, untuk tombol "lompat ke soal" di pop-up konfirmasi
    $indeksBelumDijawab = $this->soals->keys()->reject(fn ($i) => $terjawab->has($this->soals[$i]->id));
@endphp

<div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col px-4 pb-6 sm:px-6 lg:px-8"
     x-data="{ konfirmasi: false, waktuHabis: false }"
     @waktu-habis.window="konfirmasi = false; waktuHabis = true"
     @keydown.escape.window="konfirmasi = false">

    {{-- Header: Judul, Timer & Progres (menempel di atas saat scroll) --}}
    <header class="sticky top-0 z-30 -mx-4 border-b border-slate-200 bg-white/95 px-4 py-3 backdrop-blur sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
        <div class="flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="truncate text-xs font-semibold uppercase tracking-wider text-blue-600">
                    {{ $ujian->soal->mataKuliah?->nama ?? 'Ujian' }}
                </p>
                <h1 class="truncate text-base font-bold text-slate-900 sm:text-lg">{{ $ujian->judul_ujian }}</h1>
            </div>

            <div wire:ignore
                 x-data="{
                     sisa: {{ $sisaDetik }},
                     init() {
                         const akhir = Date.now() + this.sisa * 1000;
                         const timer = setInterval(() => {
                             this.sisa = Math.max(0, Math.round((akhir - Date.now()) / 1000));
                             if (this.sisa === 0) {
                                 clearInterval(timer);
                                 window.dispatchEvent(new CustomEvent('waktu-habis'));
                                 $wire.finish();
                             }
                         }, 1000);
                     },
                     get teks() {
                         const j = Math.floor(this.sisa / 3600);
                         const m = Math.floor((this.sisa % 3600) / 60);
                         const d = this.sisa % 60;
                         const dua = (n) => String(n).padStart(2, '0');
                         return (j ? dua(j) + ':' : '') + dua(m) + ':' + dua(d);
                     },
                 }"
                 class="flex shrink-0 items-center gap-2 rounded-xl px-3 py-2 text-white shadow-sm transition-colors"
                 :class="sisa <= 300 ? 'bg-red-600' : 'bg-slate-900'"
                 role="timer" aria-live="off">
                <svg class="size-5" :class="sisa <= 300 && 'animate-pulse'" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <div class="flex flex-col leading-none">
                    <span class="text-[10px] font-semibold uppercase tracking-wide opacity-70">Sisa waktu</span>
                    <span class="font-mono text-lg font-bold tabular-nums" x-text="teks">--:--</span>
                </div>
            </div>
        </div>

        {{-- Progres jawaban --}}
        <div class="mt-3 flex items-center gap-3">
            <div class="h-1.5 flex-1 overflow-hidden rounded-full bg-slate-200">
                <div class="h-full rounded-full bg-emerald-500 transition-all duration-500"
                     style="width: {{ $totalSoal ? round($jumlahTerjawab / $totalSoal * 100) : 0 }}%"></div>
            </div>
            <span class="shrink-0 text-xs font-medium text-slate-500">{{ $jumlahTerjawab }}/{{ $totalSoal }} terjawab</span>
        </div>
    </header>

    @if (! $soalAktif)
        {{-- Paket soal kosong --}}
        <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
            <p class="text-lg font-semibold text-slate-800">Ujian ini belum memiliki soal.</p>
            <p class="mt-1 text-sm text-slate-500">Silakan hubungi dosen pengampu.</p>
            <a href="{{ \App\Filament\Resources\ListUjians\ListUjianResource::getUrl() }}"
               class="mt-6 inline-flex rounded-xl bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-700">
                Kembali ke daftar ujian
            </a>
        </div>
    @else
        <div class="mt-4 grid flex-1 items-start gap-4 lg:mt-6 lg:grid-cols-[minmax(0,1fr)_18rem] lg:gap-6">

            {{-- Kolom Soal --}}
            <main class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="p-5 sm:p-8">
                    <div class="mb-5 flex flex-wrap items-center justify-between gap-2">
                        <span class="rounded-full bg-blue-600 px-3 py-1 text-xs font-bold uppercase tracking-wider text-white">
                            Soal {{ $currentSoalIndex + 1 }} dari {{ $totalSoal }}
                        </span>
                        <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-medium text-slate-500">
                            {{ $soalAktif->tipe_soal === 'pg' ? 'Pilihan Ganda' : 'Esai' }}
                        </span>
                    </div>

                    <div class="soal-content text-base leading-relaxed text-slate-800 sm:text-lg" wire:key="soal-{{ $soalAktif->id }}">
                        {!! $pertanyaanHtml !!}
                    </div>

                    @if ($soalAktif->tipe_soal === 'pg')
                        {{-- Tampilan Pilihan Ganda --}}
                        <div class="mt-6 space-y-3" role="radiogroup" aria-label="Pilihan jawaban">
                            @foreach (['a', 'b', 'c', 'd'] as $opsi)
                                @php $dipilih = $jawabanDipilih === $opsi; @endphp
                                <button type="button"
                                        wire:key="opt-{{ $soalAktif->id }}-{{ $opsi }}"
                                        wire:click="simpanJawaban('{{ $opsi }}')"
                                        wire:loading.attr="disabled"
                                        role="radio" aria-checked="{{ $dipilih ? 'true' : 'false' }}"
                                        @class([
                                            'flex w-full items-start gap-3 rounded-xl border-2 p-3 text-left transition focus:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/20 sm:gap-4 sm:p-4',
                                            'border-blue-600 bg-blue-50' => $dipilih,
                                            'border-slate-200 hover:border-blue-300 hover:bg-slate-50' => ! $dipilih,
                                        ])>
                                    <span @class([
                                        'flex size-9 shrink-0 items-center justify-center rounded-lg text-sm font-bold transition',
                                        'bg-blue-600 text-white' => $dipilih,
                                        'bg-slate-100 text-slate-500' => ! $dipilih,
                                    ])>{{ strtoupper($opsi) }}</span>
                                    <span @class([
                                        'min-w-0 break-words pt-1.5 text-sm font-medium sm:text-base',
                                        'text-blue-900' => $dipilih,
                                        'text-slate-700' => ! $dipilih,
                                    ])>{{ $soalAktif->{'opsi_'.$opsi} }}</span>
                                </button>
                            @endforeach
                        </div>
                    @else
                        {{-- Tampilan Esai --}}
                        <div class="mt-6 space-y-4" wire:key="esai-{{ $soalAktif->id }}">
                            <div class="rounded-r-xl border-l-4 border-blue-500 bg-slate-50 p-4">
                                <p class="text-xs font-bold uppercase tracking-wider text-blue-900">Petunjuk</p>
                                <p class="mt-1 text-sm leading-relaxed text-slate-600">
                                    {{ $soalAktif->petunjuk_esai ?: 'Tuliskan jawaban Anda secara lengkap.' }}
                                </p>
                            </div>

                            <div>
                                <label for="jawaban-esai" class="sr-only">Jawaban esai</label>
                                <textarea id="jawaban-esai"
                                          wire:model.live.debounce.1000ms="jawabanDipilih"
                                          rows="10"
                                          class="block min-h-56 w-full rounded-xl border-2 border-slate-200 bg-white p-4 text-base text-slate-700 shadow-inner transition placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-4 focus:ring-blue-500/10"
                                          placeholder="Ketik jawaban Anda di sini..."></textarea>
                                <div class="mt-1.5 flex items-center justify-between gap-2 text-xs">
                                    @error('jawabanDipilih')
                                        <span class="text-red-600">{{ $message }}</span>
                                    @else
                                        <span class="text-slate-400" wire:loading.remove wire:target="jawabanDipilih">Jawaban tersimpan otomatis.</span>
                                        <span class="text-blue-600" wire:loading wire:target="jawabanDipilih">Menyimpan...</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Navigasi Bawah (menempel di bawah layar pada ponsel) --}}
                <div class="sticky bottom-0 flex items-center justify-between gap-3 rounded-b-2xl border-t border-slate-200 bg-white/95 p-3 backdrop-blur sm:px-8 sm:py-4 lg:static">
                    <button type="button" wire:click="prevSoal" @disabled($currentSoalIndex === 0)
                            class="inline-flex items-center gap-1.5 rounded-xl bg-slate-100 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-200 disabled:cursor-not-allowed disabled:opacity-40 sm:px-6">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        <span>Sebelumnya</span>
                    </button>

                    @if ($currentSoalIndex < $totalSoal - 1)
                        <button type="button" wire:click="nextSoal"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-600 sm:px-6">
                            <span>Selanjutnya</span>
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    @else
                        <button type="button" @click="konfirmasi = true" aria-haspopup="dialog"
                                class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 sm:px-6">
                            <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                            <span>Kumpulkan</span>
                        </button>
                    @endif
                </div>
            </main>

            {{-- Kolom Navigasi Nomor Soal --}}
            <aside class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:sticky lg:top-28">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-xs font-bold uppercase tracking-wider text-slate-700">Navigasi Soal</h2>
                    <span class="text-xs text-slate-500">{{ $jumlahTerjawab }}/{{ $totalSoal }}</span>
                </div>

                <div class="grid grid-cols-6 gap-2 sm:grid-cols-10 lg:grid-cols-5">
                    @foreach ($this->soals as $index => $s)
                        @php $sudahDijawab = $terjawab->has($s->id); @endphp
                        <button type="button" wire:key="nav-{{ $s->id }}" wire:click="goTo({{ $index }})"
                                aria-label="Soal {{ $index + 1 }}{{ $sudahDijawab ? ', sudah dijawab' : '' }}"
                                @if ($currentSoalIndex === $index) aria-current="step" @endif
                                @class([
                                    'flex aspect-square items-center justify-center rounded-lg text-sm font-bold transition',
                                    'bg-blue-600 text-white ring-4 ring-blue-600/20' => $currentSoalIndex === $index,
                                    'bg-emerald-500 text-white hover:bg-emerald-600' => $currentSoalIndex !== $index && $sudahDijawab,
                                    'border border-slate-200 bg-slate-50 text-slate-500 hover:bg-slate-200' => $currentSoalIndex !== $index && ! $sudahDijawab,
                                ])>
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-5 flex flex-wrap gap-x-4 gap-y-2 border-t border-slate-100 pt-4 text-xs text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="size-3 rounded-full bg-blue-600"></span> Aktif</span>
                    <span class="inline-flex items-center gap-1.5"><span class="size-3 rounded-full bg-emerald-500"></span> Terjawab</span>
                    <span class="inline-flex items-center gap-1.5"><span class="size-3 rounded-full border border-slate-300 bg-slate-50"></span> Belum</span>
                </div>

                <button type="button" @click="konfirmasi = true" aria-haspopup="dialog"
                        class="mt-5 w-full rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    Selesai &amp; Kumpulkan
                </button>
            </aside>
        </div>

        {{-- Pop-up konfirmasi pengumpulan (wire:ignore.self: morph Livewire tidak menimpa display dari x-show) --}}
        <div wire:ignore.self x-cloak x-show="konfirmasi" class="fixed inset-0 z-50 flex items-end justify-center p-4 sm:items-center"
             role="dialog" aria-modal="true" aria-labelledby="judul-kumpulkan">
            <div wire:ignore.self x-show="konfirmasi" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="konfirmasi = false"></div>

            <div wire:ignore.self x-show="konfirmasi" x-trap.noscroll="konfirmasi"
                 x-transition:enter="transition duration-200 ease-out"
                 x-transition:enter-start="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="translate-y-0 opacity-100 sm:scale-100"
                 x-transition:leave="transition duration-150 ease-in"
                 x-transition:leave-start="opacity-100 sm:scale-100"
                 x-transition:leave-end="translate-y-6 opacity-0 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-md rounded-3xl bg-white p-6 text-center shadow-2xl sm:p-8">

                <div @class([
                    'mx-auto flex size-16 items-center justify-center rounded-full ring-8',
                    'bg-emerald-100 text-emerald-600 ring-emerald-50' => $belumDijawab === 0,
                    'bg-amber-100 text-amber-600 ring-amber-50' => $belumDijawab > 0,
                ])>
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        @if ($belumDijawab === 0)
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        @endif
                    </svg>
                </div>

                <h2 id="judul-kumpulkan" class="mt-5 text-xl font-bold text-slate-900">Kumpulkan ujian sekarang?</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">Setelah dikumpulkan, jawaban tidak bisa diubah lagi.</p>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <div class="rounded-2xl bg-emerald-50 p-3">
                        <p class="text-2xl font-black text-emerald-600 tabular-nums">{{ $jumlahTerjawab }}</p>
                        <p class="text-xs font-medium text-emerald-700">Sudah dijawab</p>
                    </div>
                    <div @class(['rounded-2xl p-3', 'bg-amber-50' => $belumDijawab > 0, 'bg-slate-50' => $belumDijawab === 0])>
                        <p @class(['text-2xl font-black tabular-nums', 'text-amber-600' => $belumDijawab > 0, 'text-slate-400' => $belumDijawab === 0])>{{ $belumDijawab }}</p>
                        <p @class(['text-xs font-medium', 'text-amber-700' => $belumDijawab > 0, 'text-slate-500' => $belumDijawab === 0])>Belum dijawab</p>
                    </div>
                </div>

                @if ($belumDijawab > 0)
                    <div class="mt-4 rounded-2xl border border-amber-200 bg-amber-50/60 p-4 text-left">
                        <p class="text-xs font-semibold text-amber-800">Klik nomor untuk kembali ke soal yang belum dijawab:</p>
                        <div class="mt-3 flex flex-wrap gap-2">
                            @foreach ($indeksBelumDijawab as $i)
                                <button type="button" wire:click="goTo({{ $i }})" @click="konfirmasi = false"
                                        class="flex size-9 items-center justify-center rounded-lg border border-amber-300 bg-white text-sm font-bold text-amber-700 transition hover:bg-amber-100">
                                    {{ $i + 1 }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row">
                    <button type="button" @click="konfirmasi = false"
                            class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                        Periksa lagi
                    </button>
                    <button type="button" wire:click="finish" wire:loading.attr="disabled" wire:target="finish"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700 disabled:opacity-70">
                        <svg wire:loading wire:target="finish" class="size-4 animate-spin" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                        </svg>
                        <span wire:loading.remove wire:target="finish">Ya, kumpulkan</span>
                        <span wire:loading wire:target="finish">Mengumpulkan...</span>
                    </button>
                </div>
            </div>
        </div>

        {{-- Pemberitahuan waktu habis (ujian dikumpulkan otomatis) --}}
        <div wire:ignore.self x-cloak x-show="waktuHabis" x-transition.opacity
             class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/70 p-4 backdrop-blur-sm"
             role="alertdialog" aria-modal="true" aria-labelledby="judul-waktu-habis">
            <div class="w-full max-w-sm rounded-3xl bg-white p-8 text-center shadow-2xl">
                <div class="mx-auto flex size-16 items-center justify-center rounded-full bg-red-100 text-red-600 ring-8 ring-red-50">
                    <svg class="size-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 id="judul-waktu-habis" class="mt-5 text-xl font-bold text-slate-900">Waktu habis</h2>
                <p class="mt-2 text-sm leading-relaxed text-slate-500">Jawaban Anda sedang dikumpulkan secara otomatis. Mohon tunggu sebentar...</p>
                <svg class="mx-auto mt-6 size-6 animate-spin text-slate-400" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"/>
                </svg>
            </div>
        </div>
    @endif
</div>
