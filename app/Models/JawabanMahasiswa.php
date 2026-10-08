<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JawabanMahasiswa extends Model
{
    protected $fillable = ['ujian_attempt_id', 'detail_soal_id', 'jawaban', 'is_benar', 'nilai_esai', 'catatan_dosen'];

    protected $casts = [
        'is_benar' => 'boolean',
        'nilai_esai' => 'integer',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(UjianAttempt::class, 'ujian_attempt_id');
    }

    public function detailSoal(): BelongsTo
    {
        return $this->belongsTo(DetailSoal::class, 'detail_soal_id');
    }
}
