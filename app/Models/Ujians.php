<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Ujians extends Model
{
    // Daftarkan kolom agar bisa disimpan oleh Filament
    protected $fillable = [
        'judul_ujian',
        'user_id',
        'soals_id',
        'waktu_mulai',
        'waktu_selesai',
        'durasi_menit',
    ];

    protected $casts = [
        'waktu_mulai' => 'datetime',
        'waktu_selesai' => 'datetime',
        'durasi_menit' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function soal(): BelongsTo
    {
        return $this->belongsTo(Soals::class, 'soals_id');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(UjianAttempt::class, 'ujian_id');
    }

    // Butir soal dari paket soal yang dipakai ujian ini (detail_soal.soals_id = ujians.soals_id)
    public function detailSoals(): HasMany
    {
        return $this->hasMany(DetailSoal::class, 'soals_id', 'soals_id')->orderBy('nomor_soal');
    }

    // Ujian memiliki satu Matkul melalui Soal
    public function mataKuliah(): HasOneThrough
    {
        return $this->hasOneThrough(
            MataKuliah::class,
            Soals::class,
            'id', // Foreign key di tabel soals (id soal)
            'id', // Foreign key di tabel mata_kuliahs (id matkul)
            'soals_id', // Local key di tabel ujians
            'mata_kuliah_id' // Local key di tabel soals yang mengarah ke matkul
        );
    }

    // Attempt milik user tertentu (memakai relasi attempts yang sudah dimuat bila ada)
    public function attemptOleh(int $userId): ?UjianAttempt
    {
        return $this->attempts->firstWhere('user_id', $userId);
    }

    public function isSudahBerakhir(): bool
    {
        return now()->greaterThan($this->waktu_selesai);
    }

    public function isBerlangsung(): bool
    {
        return now()->between($this->waktu_mulai, $this->waktu_selesai);
    }
}
