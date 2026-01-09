<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Soals extends Model
{
    protected $fillable = [
        'setting_soal_id',
        'mata_kuliah_id',
        'user_id',
        'nama_soal', // Tambahkan ini
        'jenis_soal',
        'tipe_soal',
    ];

    public function mataKuliah(): BelongsTo
    {
        return $this->belongsTo(MataKuliah::class, 'mata_kuliah_id');
    }

    public function detailSoals(): HasMany
    {
        // Pastikan foreign key di detail_soal adalah 'soals_id' sesuai migrasi Anda
        return $this->hasMany(DetailSoal::class, 'soals_id');
    }

    // Tambahkan juga relasi ke SettingSoal agar dropdown di form berfungsi
    public function settingSoal()
    {
        return $this->belongsTo(SettingSoal::class);
    }

    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
}
