<div class="min-h-screen bg-[#0f172a] py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <div class="flex justify-between items-center mb-8 bg-white/10 p-4 rounded-xl backdrop-blur-md border border-white/20">
            <h2 class="text-xl font-bold text-white tracking-wide">
                <span class="opacity-70 font-normal">Ujian:</span> {{ $attempt->ujian->nama_soal }}
            </h2>

            <div class="text-white font-mono bg-red-600 px-6 py-2 rounded-lg shadow-lg flex items-center gap-3"
                 x-data="timerData()"
                 x-init="startTimer()">
                <svg class="w-5 h-5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xl font-bold" x-text="formatTime(seconds)"></span>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8">
                <div class="bg-white rounded-3xl shadow-2xl p-8 lg:p-10 min-h-[550px] flex flex-col justify-between border border-gray-100">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <span class="bg-blue-600 text-white px-4 py-1 rounded-full text-xs font-black uppercase tracking-widest">
                                Soal Nomor {{ $currentSoalIndex + 1 }}
                            </span>
                            <span class="text-gray-400 text-sm font-medium">Total: {{ count($soals) }} Soal</span>
                        </div>

                        <div class="mt-6 text-2xl text-slate-800 leading-relaxed font-medium">
                            {!! $soalAktif->pertanyaan !!}
                        </div>

                        <div class="mt-10 space-y-4">
                            @foreach(['a', 'b', 'c', 'd'] as $opsi)
                                @php $field = "opsi_" . $opsi; @endphp
                                <button
                                    wire:click="simpanJawaban('{{ $opsi }}')"
                                    class="w-full flex items-center p-5 border-2 rounded-2xl transition-all duration-200 group
                                    {{ $jawabanDipilih === $opsi
                                        ? 'border-blue-500 bg-blue-50 ring-4 ring-blue-500/10'
                                        : 'border-slate-100 bg-white hover:border-blue-200 hover:bg-slate-50' }}">

                                    <div class="w-12 h-12 flex-shrink-0 flex items-center justify-center rounded-xl font-black text-lg transition-all
                                        {{ $jawabanDipilih === $opsi ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-500 group-hover:bg-blue-100' }}">
                                        {{ strtoupper($opsi) }}
                                    </div>
                                    <div class="ml-5 text-left text-lg font-semibold {{ $jawabanDipilih === $opsi ? 'text-blue-900' : 'text-slate-700' }}">
                                        {{ $soalAktif->$field }}
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-between mt-12 pt-8 border-t border-slate-100">
                        <button wire:click="prevSoal"
                            class="px-8 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold hover:bg-slate-200 disabled:opacity-30 transition-all"
                            @if($currentSoalIndex == 0) disabled @endif>
                            Sebelumnya
                        </button>

                        @if($currentSoalIndex < count($soals) - 1)
                            <button wire:click="nextSoal" class="px-8 py-3 bg-slate-900 text-white rounded-xl font-bold hover:bg-blue-700 shadow-lg transition-all">
                                Selanjutnya
                            </button>
                        @else
                            <button wire:click="finish"
                                onclick="return confirm('Apakah Anda yakin ingin menyelesaikan ujian?')"
                                class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black shadow-lg hover:bg-emerald-700 transition-all">
                                SELESAI & KUMPULKAN
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4">
                <div class="bg-white rounded-3xl shadow-xl p-6 border border-gray-100 sticky top-8">
                    <h3 class="font-black text-slate-800 uppercase tracking-widest text-sm mb-6 flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                        Navigasi Soal
                    </h3>
                    <div class="grid grid-cols-5 gap-3">
                        @foreach($soals as $index => $s)
                            @php
                                $sudahDijawab = \App\Models\JawabanMahasiswa::where('ujian_attempt_id', $attempt->id)
                                                ->where('detail_soal_id', $s->id)
                                                ->exists();
                            @endphp
                            <button
                                wire:click="$set('currentSoalIndex', {{ $index }}); loadJawabanTersimpan();"
                                class="h-12 rounded-xl flex items-center justify-center font-bold text-sm transition-all
                                {{ $currentSoalIndex == $index
                                    ? 'bg-blue-600 text-white ring-4 ring-blue-600/20 scale-110 shadow-lg z-10'
                                    : ($sudahDijawab ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200 border border-emerald-200' : 'bg-slate-50 text-slate-400 hover:bg-slate-100 border border-slate-100') }}">
                                {{ $index + 1 }}
                            </button>
                        @endforeach
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50 flex flex-wrap gap-4 justify-center text-[10px] font-bold uppercase tracking-tighter text-slate-400">
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-blue-600"></span> Aktif</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Terjawab</div>
                        <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-slate-200"></span> Belum</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function timerData() {
            return {
                seconds: @js($sisaWaktu),
                startTimer() {
                    if (this.seconds <= 0) return;
                    let interval = setInterval(() => {
                        if (this.seconds > 0) {
                            this.seconds--;
                            // Sync ke Livewire setiap 30 detik
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
                    if (totalSeconds <= 0) return "00:00:00";
                    const h = Math.floor(totalSeconds / 3600).toString().padStart(2, '0');
                    const m = Math.floor((totalSeconds % 3600) / 60).toString().padStart(2, '0');
                    const s = Math.floor(totalSeconds % 60).toString().padStart(2, '0');
                    return `${h}:${m}:${s}`;
                }
            }
        }
    </script>
</div>
