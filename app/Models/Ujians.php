<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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

    // app/Models/Ujian.php
public function user() {
    return $this->belongsTo(User::class);
}

public function soal() {
    return $this->belongsTo(Soals::class, 'soals_id');
}

public function attempts()
{
    return $this->hasMany(UjianAttempt::class, 'ujian_id');
}

// Di Model Ujians/Soals
public function detailSoals()
{
    return $this->hasMany(DetailSoal::class, 'soals_id');
}

public function mataKuliah(): BelongsTo
{
    // Ujian memiliki satu Matkul melalui Soal
    return $this->hasOneThrough(
        MataKuliah::class,
        Soals::class,
        'id', // Foreign key di tabel soals (id soal)
        'id', // Foreign key di tabel mata_kuliahs (id matkul)
        'soals_id', // Local key di tabel ujians
        'mata_kuliah_id' // Local key di tabel soals yang mengarah ke matkul
    );
}

public function mahasiswas(): BelongsToMany
{
    // Asumsi ada tabel pivot 'krs' atau 'mata_kuliah_user'
    return $this->belongsToMany(User::class, 'krs', 'mata_kuliah_id', 'user_id');
}

}
