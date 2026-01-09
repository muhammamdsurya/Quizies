<div class="py-12 max-w-5xl mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border">
                <div class="flex justify-between items-center mb-4">
                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-sm font-bold">
                        Soal Nomor {{ $currentSoalIndex + 1 }}
                    </span>
                </div>

                <div class="prose max-w-none mb-8 text-black ">
                    {!! $soalAktif->pertanyaan !!}
                </div>

                <div class="space-y-3 text-black">
                    @foreach(['a', 'b', 'c', 'd'] as $opsi)
                    <label class="flex items-center p-4 border rounded-lg cursor-pointer transition {{ $jawabanDipilih == $opsi ? 'bg-blue-50 border-blue-500 ring-1 ring-blue-500' : 'hover:bg-gray-50' }}">
                        <input type="radio" wire:click="simpanJawaban('{{ $opsi }}')" name="jawaban" value="{{ $opsi }}" {{ $jawabanDipilih == $opsi ? 'checked' : '' }} class="hidden">
                        <span class="w-8 h-8 flex items-center justify-center rounded-full border mr-4 {{ $jawabanDipilih == $opsi ? 'bg-blue-500 text-white' : 'bg-gray-100' }}">
                            {{ strtoupper($opsi) }}
                        </span>
                        <div class="text-black">
                            {{ $soalAktif->{'opsi_' . $opsi} }}
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-between mt-4">
                <button wire:click="prevSoal" @disabled($currentSoalIndex == 0) class="px-6 py-2 bg-white border rounded-lg disabled:opacity-50">Sebelumnya</button>

                @if($currentSoalIndex < count($soals) - 1)
                    <button wire:click="nextSoal" class="px-6 py-2 bg-blue-600 text-white rounded-lg">Selanjutnya</button>
                @else
                    <button
    type="button"
    wire:click="finish"
    wire:confirm="Apakah Anda yakin ingin mengumpulkan jawaban? Anda tidak dapat mengubah jawaban setelah ini."
    class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-bold transition flex items-center gap-2"
>
    <x-heroicon-s-check-circle class="w-5 h-5"/>
    Kumpulkan Jawaban
</button>
                @endif
            </div>
        </div>

        <div class="md:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-sm border sticky top-6">
                <h3 class="font-bold mb-4 text-gray-900 border-b pb-2 text-center">Navigasi Soal</h3>
                <div class="grid grid-cols-5 gap-2">
                    @foreach($soals as $index => $s)
                    <button wire:click="$set('currentSoalIndex', {{ $index }}); $dispatch('loadJawabanTersimpan')"
                        class="w-10 h-10 flex items-center justify-center rounded text-sm font-medium border transition
                        {{ $currentSoalIndex == $index ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-600 hover:bg-gray-100' }}">
                        {{ $index + 1 }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>
