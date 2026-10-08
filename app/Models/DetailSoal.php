<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DetailSoal extends Model
{
    protected $table = 'detail_soal'; // Sesuai nama tabel di migrasi

    protected $fillable = [
        'soals_id',
        'nomor_soal',
        'pertanyaan',
        'tipe_soal',
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'kunci_jawaban',
        'petunjuk_esai',
    ];

    protected static function booted()
    {
        static::creating(function ($detailSoal) {
            // Jika nomor_soal belum terisi dari form, lanjutkan dari nomor terakhir
            if (blank($detailSoal->nomor_soal)) {
                $lastNumber = static::where('soals_id', $detailSoal->soals_id)->max('nomor_soal');
                $detailSoal->nomor_soal = ($lastNumber ?? 0) + 1;
            }
        });

        // Tipe soal selalu mengikuti paket soal induknya
        static::saving(function ($detailSoal) {
            if ($tipe = Soals::whereKey($detailSoal->soals_id)->value('tipe_soal')) {
                $detailSoal->tipe_soal = $tipe;
            }
        });
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soals::class, 'soals_id');
    }
}
