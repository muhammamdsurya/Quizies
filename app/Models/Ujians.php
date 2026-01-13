<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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



}
