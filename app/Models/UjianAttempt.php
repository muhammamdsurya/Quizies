<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UjianAttempt extends Model
{
    protected $fillable = ['user_id', 'ujian_id', 'mulai_pada', 'selesai_pada', 'skor_akhir'];

// WAJIB DITAMBAHKAN: Relasi ke tabel Ujians
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
}
