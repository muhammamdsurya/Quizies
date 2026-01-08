<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SettingSoal extends Model
{
    use HasFactory;

    protected $fillable = [
        'tahun_akademik',
        'jenis_soal_options',
        'tipe_soal_options',
        'is_active',
    ];

    // Mengubah string JSON di database menjadi array otomatis
    protected $casts = [
        'jenis_soal_options' => 'array',
        'tipe_soal_options' => 'array',
        'is_active' => 'boolean',
    ];

    public function soals(): HasMany
    {
        return $this->hasMany(Soal::class);
    }
}
