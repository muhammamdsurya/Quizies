<div class="w-full">
    {{-- CSS Kustom untuk Memastikan Sidebar Benar-benar Hilang --}}
    <style>
        /* Sembunyikan navigasi atau sidebar bawaan jika masih ada yang bocor */
        nav, .sidebar, aside { display: none !important; }
        body { background-color: #0f172a !important; } /* Paksa background gelap sesuai tema */
    </style>

    {{-- Header: Judul & Timer --}}
    <div class="flex flex-col md:flex-row justify-between items-center mb-8 bg-white/10 p-6 rounded-2xl backdrop-blur-md border border-white/20 shadow-xl">
        <div class="mb-4 md:mb-0">
            <h2 class="text-2xl font-black text-white tracking-tight">
                <span class="opacity-50 font-light text-sm block uppercase tracking-widest">Sedang Berlangsung</span>
                {{ $attempt->ujian->judul_ujian }}
            </h2>
        </div>

        <div class="text-white font-mono bg-red-600 px-8 py-3 rounded-xl shadow-[0_0_20px_rgba(220,38,38,0.5)] flex items-center gap-4 border-2 border-red-400"
             x-data="timerData()"
             x-init="startTimer()">
            <svg class="w-6 h-6 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="flex flex-col leading-none">
                <span class="text-[10px] uppercase font-bold opacity-70">Sisa Waktu</span>
                <span class="text-2xl font-black" x-text="formatTime(seconds)"></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-8">
        {{-- Kolom Kiri: Soal --}}
        <div class="col-span-12 lg:col-span-8">
            <div class="bg-white rounded-[2rem] shadow-2xl p-8 lg:p-12 min-h-[600px] flex flex-col justify-between border border-white/10 relative overflow-hidden">
                {{-- Dekorasi Nomor Soal --}}
                <div class="absolute top-0 right-0 p-8 opacity-[0.03] pointer-events-none">
                    <span class="text-9xl font-black">{{ $currentSoalIndex + 1 }}</span>
                </div>

                <div>
                    <div class="flex justify-between items-center mb-10">
                        <span class="bg-blue-600 text-white px-6 py-2 rounded-full text-xs font-black uppercase tracking-[0.2em] shadow-lg shadow-blue-500/30">
                            Pertanyaan {{ $currentSoalIndex + 1 }}
                        </span>
                        <span class="text-slate-400 text-sm font-bold bg-slate-100 px-4 py-1 rounded-lg italic">
                            {{ count($soals) }} Total Soal
                        </span>
                    </div>

                    <div class="mt-8 text-2xl text-slate-800 leading-relaxed font-semibold">
                        {!! $soalAktif->pertanyaan !!}
                    </div>

                    <div class="mt-12 space-y-5">
    @if($soalAktif->tipe_soal === 'pg')
        {{-- Tampilan Pilihan Ganda --}}
        @foreach(['a', 'b', 'c', 'd'] as $opsi)
            @php $field = "opsi_" . $opsi; @endphp
            <button
                wire:key="opt-{{ $soalAktif->id }}-{{ $opsi }}"
                wire:click="simpanJawaban('{{ $opsi }}')"
                class="w-full flex items-center p-6 border-2 rounded-2xl transition-all duration-300 group
                {{ $jawabanDipilih === $opsi
                    ? 'border-blue-600 bg-blue-50/50 ring-4 ring-blue-600/10'
                    : 'border-slate-100 bg-white hover:border-blue-300 hover:shadow-xl hover:-translate-y-1' }}">

                <div class="w-14 h-14 flex-shrink-0 flex items-center justify-center rounded-xl font-black text-xl transition-all duration-300
                    {{ $jawabanDipilih === $opsi ? 'bg-blue-600 text-white shadow-lg' : 'bg-slate-100 text-slate-400 group-hover:bg-blue-100 group-hover:text-blue-600' }}">
                    {{ strtoupper($opsi) }}
                </div>
                <div class="ml-6 text-left text-lg font-bold {{ $jawabanDipilih === $opsi ? 'text-blue-900' : 'text-slate-600' }}">
                    {{ $soalAktif->$field }}
                </div>
            </button>
        @endforeach

    @elseif($soalAktif->tipe_soal === 'esai')
        {{-- Tampilan Esai --}}
        <div class="space-y-6" wire:key="esai-box-{{ $soalAktif->id }}">
            <div class="bg-slate-50 border-l-4 border-blue-500 p-6 rounded-r-2xl mb-6">
                <h4 class="text-sm font-black text-blue-900 uppercase tracking-widest mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Petunjuk Esai
                </h4>
                <p class="text-slate-600 leading-relaxed italic">
                    {{ $soalAktif->petunjuk_esai ?? 'Tuliskan jawaban Anda secara lengkap.' }}
                </p>
            </div>

            <div class="relative group">
                <textarea
                    wire:model.lazy="jawabanDipilih" 
                    wire:blur="simpanJawaban($event.target.value)"
                    class="w-full p-8 border-2 border-slate-100 rounded-[2rem] focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 min-h-[300px] text-lg font-medium text-slate-700 transition-all shadow-inner bg-slate-50/30"
                    placeholder="Ketik jawaban Anda di sini..."></textarea>
            </div>
        </div>
    @endif
</div>
                </div>

                {{-- Navigasi Bawah --}}
                <div class="flex justify-between mt-16 pt-10 border-t-2 border-slate-50">
                    <button wire:click="prevSoal"
                        class="group flex items-center gap-2 px-10 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black hover:bg-slate-200 disabled:opacity-20 transition-all"
                        @if($currentSoalIndex == 0) disabled @endif>
                        <svg class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
                        SEBELUMNYA
                    </button>

                    @if($currentSoalIndex < count($soals) - 1)
                        <button wire:click="nextSoal" class="group flex items-center gap-2 px-10 py-4 bg-slate-900 text-white rounded-2xl font-black hover:bg-blue-600 shadow-xl transition-all">
                            SELANJUTNYA
                            <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                    @else
                        <button 
    wire:click="finish"
    wire:confirm="Apakah Anda yakin ingin mengakhiri ujian? Jawaban tidak bisa diubah kembali."
    class="px-12 py-4 bg-emerald-500 text-white rounded-2xl font-black shadow-xl ...">
    Selesai & Kumpulkan
</button>
                    @endif
                </div>
            </div>
        </div>

        {{-- Kolom Kanan: Navigasi Nomor --}}
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-white rounded-[2rem] shadow-2xl p-8 border border-white/10 sticky top-8">
                <h3 class="font-black text-slate-800 uppercase tracking-[0.2em] text-xs mb-8 flex items-center gap-3">
                    <span class="w-2 h-8 bg-blue-600 rounded-full"></span>
                    Navigasi Panel
                </h3>
                
                <div class="grid grid-cols-4 sm:grid-cols-5 gap-4">
                    @foreach($soals as $index => $s)
                        @php
                            $sudahDijawab = \App\Models\JawabanMahasiswa::where('ujian_attempt_id', $attempt->id)
                                            ->where('detail_soal_id', $s->id)
                                            ->exists();
                        @endphp
                        <button
                            wire:click="$set('currentSoalIndex', {{ $index }}); loadJawabanTersimpan();"
                            class="h-14 rounded-2xl flex items-center justify-center font-black text-sm transition-all duration-300
                            {{ $currentSoalIndex == $index
                                ? 'bg-blue-600 text-white ring-8 ring-blue-600/10 scale-110 shadow-xl shadow-blue-600/40 z-10'
                                : ($sudahDijawab 
                                    ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-500/20 hover:bg-emerald-600' 
                                    : 'bg-slate-50 text-slate-400 hover:bg-slate-200 border border-slate-100') }}">
                            {{ $index + 1 }}
                        </button>
                    @endforeach
                </div>

                <div class="mt-10 pt-8 border-t border-slate-100 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <span class="w-4 h-4 rounded-full bg-emerald-500 shadow-sm"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">Terjawab</span>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-slate-50 rounded-xl">
                        <span class="w-4 h-4 rounded-full bg-slate-300 shadow-sm"></span>
                        <span class="text-[10px] font-black text-slate-500 uppercase tracking-tighter">Belum</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Timer --}}
    <script>
        function timerData() {
            return {
                seconds: @js($sisaWaktu),
                startTimer() {
                    if (this.seconds <= 0) return;
                    let interval = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                            if (this.seconds % 30 === 0) {
                                @this.set('sisaWaktu', this.seconds);
                            }
                        } else {
                            clearInterval(interval);
                            @this.finish();
                        }
                    }, 1000);
                },
                formatTime(totalSeconds) {
                    const h = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
                    const m = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
                    const s = Math.floor(totalSeconds % 60).toString().padStart(2, '0');
                    return `${h}:${m}:${s}`;
                }
            }
        }
    </script>
</div>