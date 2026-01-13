<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UjianAttempt;
use App\Models\JawabanMahasiswa;
use App\Models\DetailSoal;
use App\Filament\Resources\ListUjians\ListUjianResource;

class KerjakanUjian extends Component
{
   public $attempt;
    public $soals;
    public $currentSoalIndex = 0;
    public $jawabanDipilih;
public $sisaWaktu; // Dalam detik

    public function mount($attempt_id)
    {
        // Ambil data attempt beserta soal-soalnya
        $this->attempt = UjianAttempt::with(['ujian.soal.detailSoals'])->findOrFail($attempt_id);


        // Ambil durasi_menit dari tabel ujians
    $durasiMenit = $this->attempt->ujian->durasi_menit ?? 60; // Default 60 jika null

    // Konversi ke detik untuk timer Javascript
    $this->sisaWaktu = $durasiMenit * 60;

        $this->soals = $this->attempt->ujian->soal->detailSoals;

        if ($this->attempt->selesai_pada) {
       return redirect()->to(ListUjianResource::getUrl('index'));
    }

        if ($this->soals->isEmpty()) {
        session()->flash('error', 'Bank soal ini belum memiliki pertanyaan.');
    }

        // Pastikan mahasiswa tidak mengakses attempt milik orang lain
        if ($this->attempt->user_id !== auth()->id()) {
            abort(403);
        }

        $this->soals = $this->attempt->ujian->soal->detailSoals;
        $this->loadJawabanTersimpan();
    }

    public function loadJawabanTersimpan()
    {
        $soalId = $this->soals[$this->currentSoalIndex]->id;
        $jawaban = JawabanMahasiswa::where('ujian_attempt_id', $this->attempt->id)
            ->where('detail_soal_id', $soalId)
            ->first();

        $this->jawabanDipilih = $jawaban ? $jawaban->jawaban : null;
    }

    public function simpanJawaban($pilihan)
    {
        $this->jawabanDipilih = $pilihan;
        $soalAktif = $this->soals[$this->currentSoalIndex];

        JawabanMahasiswa::updateOrCreate(
            [
                'ujian_attempt_id' => $this->attempt->id,
                'detail_soal_id' => $soalAktif->id,
            ],
            [
                'jawaban' => $pilihan,
                'is_benar' => ($soalAktif->kunci_jawaban === $pilihan), // Auto-grading untuk PG
            ]
        );
    }

    public function nextSoal()
    {
        if ($this->currentSoalIndex < count($this->soals) - 1) {
            $this->currentSoalIndex++;
            $this->loadJawabanTersimpan();
        }
    }

    public function prevSoal()
    {
        if ($this->currentSoalIndex > 0) {
            $this->currentSoalIndex--;
            $this->loadJawabanTersimpan();
        }
    }

    public function render()
    {
        return view('livewire.kerjakan-ujian', [
            'soalAktif' => $this->soals[$this->currentSoalIndex]
        ])->layout('components.layouts.app'); // Pastikan Anda punya resources/views/layouts/app.blade.php
    }

    public function finish()
{
    // 1. Ambil semua jawaban mahasiswa untuk attempt ini
    $jawabanMahasiswa = \App\Models\JawabanMahasiswa::where('ujian_attempt_id', $this->attempt->id)->get();

    // 2. Hitung jumlah jawaban yang benar
    // Asumsi: Kita sudah menyimpan status 'is_benar' saat mahasiswa memilih opsi
    $jumlahBenar = $jawabanMahasiswa->where('is_benar', true)->count();
    $totalSoal = $this->soals->count();

    // 3. Kalkulasi skor (skala 0 - 100)
    $skorAkhir = ($totalSoal > 0) ? ($jumlahBenar / $totalSoal) * 100 : 0;

    // 4. Update data attempt di database
    $this->attempt->update([
        'selesai_pada' => now(),
        'skor_akhir' => round($skorAkhir, 2),
    ]);

    // 5. Kirim notifikasi sukses dan redirect
    session()->flash('message', "Ujian selesai! Skor Anda: " . round($skorAkhir, 2));

    return redirect()->route('filament.admin.resources.list-ujians.index');
}
}
