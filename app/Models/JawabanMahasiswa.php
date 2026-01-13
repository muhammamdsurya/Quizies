<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanMahasiswa extends Model
{
    protected $fillable = ['ujian_attempt_id', 'detail_soal_id', 'jawaban', 'is_benar'];

    // TAMBAHKAN INI: Relasi ke UjianAttempt
    public function attempt(): BelongsTo
    {
        // Pastikan nama kolom foreign key adalah 'ujian_attempt_id'
        return $this->belongsTo(UjianAttempt::class, 'ujian_attempt_id');
    }

    // TAMBAHKAN JUGA: Relasi ke DetailSoal agar mudah dipanggil
    public function detailSoal(): BelongsTo
    {
        return $this->belongsTo(DetailSoal::class, 'detail_soal_id');
    }
}
