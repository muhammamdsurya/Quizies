<?php

namespace App\Livewire;

use App\Filament\Resources\RiwayatUjians\RiwayatUjianResource;
use App\Models\DetailSoal;
use App\Models\JawabanMahasiswa;
use App\Models\UjianAttempt;
use Filament\Notifications\Notification;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class KerjakanUjian extends Component
{
    // Locked: tidak bisa diubah dari browser, hanya lewat method di bawah
    #[Locked]
    public int $attemptId;

    #[Locked]
    public int $currentSoalIndex = 0;

    public ?string $jawabanDipilih = null;

    public function mount(int $attempt_id): void
    {
        $attempt = UjianAttempt::findOrFail($attempt_id);

        // Pastikan mahasiswa tidak mengakses attempt milik orang lain
        abort_unless($attempt->user_id === auth()->id(), 403);

        $this->attemptId = $attempt->id;

        if ($attempt->isSelesai() || $attempt->isWaktuHabis()) {
            $this->kumpulkan();

            return;
        }

        $this->loadJawabanTersimpan();
    }

    #[Computed]
    public function attempt(): UjianAttempt
    {
        return UjianAttempt::with('ujian.soal.mataKuliah', 'ujian.soal.detailSoals')->findOrFail($this->attemptId);
    }

    #[Computed]
    public function soals(): Collection
    {
        return $this->attempt->ujian->soal->detailSoals->values();
    }

    private function soalAktif(): ?DetailSoal
    {
        return $this->soals->get($this->currentSoalIndex);
    }

    public function loadJawabanTersimpan(): void
    {
        $soal = $this->soalAktif();

        $this->jawabanDipilih = $soal
            ? JawabanMahasiswa::where('ujian_attempt_id', $this->attemptId)->where('detail_soal_id', $soal->id)->value('jawaban')
            : null;
    }

    // Pilihan ganda: dipanggil saat opsi diklik
    public function simpanJawaban(string $pilihan): void
    {
        $soal = $this->soalAktif();

        if ($this->tutupJikaBerakhir() || ! $soal || $soal->tipe_soal !== 'pg' || ! in_array($pilihan, ['a', 'b', 'c', 'd'], true)) {
            return;
        }

        $this->jawabanDipilih = $pilihan;
        $this->simpan($soal, $pilihan);
    }

    // Esai: tersimpan otomatis saat mengetik (wire:model.live.debounce)
    public function updatedJawabanDipilih(): void
    {
        $soal = $this->soalAktif();

        if ($this->tutupJikaBerakhir() || ! $soal) {
            return;
        }

        if ($soal->tipe_soal !== 'esai') {
            $this->loadJawabanTersimpan(); // jawaban PG hanya boleh lewat simpanJawaban()

            return;
        }

        $this->validate(['jawabanDipilih' => ['nullable', 'string', 'max:20000']], [
            'jawabanDipilih.max' => 'Jawaban maksimal 20.000 karakter.',
        ]);

        $this->simpan($soal, $this->jawabanDipilih);
    }

    private function simpan(DetailSoal $soal, ?string $jawaban): void
    {
        JawabanMahasiswa::updateOrCreate(
            ['ujian_attempt_id' => $this->attemptId, 'detail_soal_id' => $soal->id],
            ['jawaban' => $jawaban, 'is_benar' => $soal->tipe_soal === 'pg' ? $jawaban === $soal->kunci_jawaban : null],
        );
    }

    public function goTo(int $index): void
    {
        if ($index < 0 || $index >= $this->soals->count()) {
            return;
        }

        $this->currentSoalIndex = $index;
        $this->loadJawabanTersimpan();
        $this->resetValidation();
    }

    public function nextSoal(): void
    {
        $this->goTo($this->currentSoalIndex + 1);
    }

    public function prevSoal(): void
    {
        $this->goTo($this->currentSoalIndex - 1);
    }

    public function finish(): void
    {
        $this->kumpulkan();
    }

    // Waktu habis / sudah dikumpulkan: tolak perubahan jawaban dan kumpulkan
    private function tutupJikaBerakhir(): bool
    {
        if ($this->attempt->isSelesai() || $this->attempt->isWaktuHabis()) {
            $this->kumpulkan();

            return true;
        }

        return false;
    }

    private function kumpulkan(): void
    {
        $attempt = $this->attempt;
        $sudahDikumpulkan = $attempt->isSelesai();

        $attempt->selesaikan();

        Notification::make()
            ->success()
            ->title($sudahDikumpulkan ? 'Ujian ini sudah dikumpulkan.' : 'Ujian berhasil dikumpulkan.')
            ->body(match (true) {
                $attempt->isMenungguPenilaian() => 'Nilai akhir akan muncul setelah dosen menilai jawaban esai.',
                default => "Nilai Anda: {$attempt->skor_akhir}",
            })
            ->send();

        $this->redirect(RiwayatUjianResource::getUrl('view', ['record' => $attempt->ujian_id]));
    }

    public function render()
    {
        $soalAktif = $this->soalAktif();

        $terjawab = JawabanMahasiswa::where('ujian_attempt_id', $this->attemptId)
            ->whereNotNull('jawaban')
            ->where('jawaban', '!=', '')
            ->pluck('detail_soal_id')
            ->flip();

        return view('livewire.kerjakan-ujian', [
            'ujian' => $this->attempt->ujian,
            'soalAktif' => $soalAktif,
            'pertanyaanHtml' => $soalAktif
                ? Str::markdown($soalAktif->pertanyaan, ['html_input' => 'escape', 'allow_unsafe_links' => false])
                : null,
            'terjawab' => $terjawab,
            'sisaDetik' => $this->attempt->sisaDetik(),
        ])
            ->layout('components.layouts.ujian')
            ->title($this->attempt->ujian->judul_ujian);
    }
}
