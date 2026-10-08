<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class UjianAttempt extends Model
{
    protected $fillable = ['user_id', 'ujian_id', 'mulai_pada', 'selesai_pada', 'skor_akhir'];

    protected $casts = [
        'mulai_pada' => 'datetime',
        'selesai_pada' => 'datetime',
        'skor_akhir' => 'integer',
    ];

    public function ujian(): BelongsTo
    {
        return $this->belongsTo(Ujians::class, 'ujian_id');
    }

    // Relasi ke tabel User (Mahasiswa)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jawabanMahasiswas(): HasMany
    {
        return $this->hasMany(JawabanMahasiswa::class);
    }

    /**
     * Batas waktu pengerjaan: durasi sejak mulai, tapi tidak boleh melewati jadwal selesai ujian.
     */
    public function batasWaktu(): Carbon
    {
        return $this->mulai_pada->copy()
            ->addMinutes($this->ujian->durasi_menit)
            ->min($this->ujian->waktu_selesai);
    }

    public function sisaDetik(): int
    {
        return max(0, (int) now()->diffInSeconds($this->batasWaktu()));
    }

    public function isWaktuHabis(): bool
    {
        return now()->greaterThanOrEqualTo($this->batasWaktu());
    }

    public function isSelesai(): bool
    {
        return $this->selesai_pada !== null;
    }

    public function selesaikan(): void
    {
        if ($this->isSelesai()) {
            return;
        }

        $this->selesai_pada = now();
        $this->hitungSkor();
    }

    /**
     * Skor 0-100: rata-rata nilai per soal. PG benar = 100, esai = nilai_esai dari dosen.
     * Skor null selama masih ada jawaban esai yang belum dinilai.
     */
    public function hitungSkor(): void
    {
        $jawaban = $this->jawabanMahasiswas()->get()->keyBy('detail_soal_id');

        $nilai = $this->ujian->soal->detailSoals->map(function (DetailSoal $soal) use ($jawaban) {
            $j = $jawaban->get($soal->id);

            if ($soal->tipe_soal === 'esai') {
                return blank($j?->jawaban) ? 0 : $j->nilai_esai;
            }

            return $j && $j->jawaban === $soal->kunci_jawaban ? 100 : 0;
        });

        $this->skor_akhir = $nilai->containsStrict(null) ? null : (int) round($nilai->avg() ?? 0);
        $this->save();
    }

    /**
     * Kumpulkan otomatis attempt yang waktunya habis tapi belum disubmit (misal tab ditutup).
     */
    public static function selesaikanYangKedaluwarsa(int $userId): void
    {
        static::with('ujian.soal.detailSoals')
            ->where('user_id', $userId)
            ->whereNull('selesai_pada')
            ->get()
            ->filter->isWaktuHabis()
            ->each->selesaikan();
    }

    public function isMenungguPenilaian(): bool
    {
        return $this->isSelesai() && $this->skor_akhir === null;
    }
}
