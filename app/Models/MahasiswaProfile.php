<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MahasiswaProfile extends Model
{
     protected $fillable = [
        'user_id',
        'nim',
        'prodi_id',
        'semester',
        'tahun_masuk',
        'tanggal_masuk',
        'is_active',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
