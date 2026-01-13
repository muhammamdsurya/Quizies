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
        // Jika nomor_soal belum terisi dari form
        if (blank($detailSoal->nomor_soal)) {
            // Hitung jumlah soal yang sudah ada untuk ID Soal (header) tersebut
            $lastNumber = static::where('soals_id', $detailSoal->soals_id)->max('nomor_soal');
            $detailSoal->nomor_soal = ($lastNumber ?? 0) + 1;
        }

        if ($detailSoal->soals_id) {
                $induk = \App\Models\Soals::find($detailSoal->soals_id);
                if ($induk) {
                    $detailSoal->tipe_soal = $induk->tipe_soal;
                }
            }
    });

    static::saving(function ($detailSoal) {
             if ($detailSoal->soals_id) {
                $induk = \App\Models\Soals::find($detailSoal->soals_id);
                if ($induk) {
                    $detailSoal->tipe_soal = $induk->tipe_soal;
                }
            }
        });
}
}
