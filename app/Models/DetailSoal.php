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
        'opsi_a',
        'opsi_b',
        'opsi_c',
        'opsi_d',
        'kunci_jawaban',
        'petunjuk_esai'
    ];
}
