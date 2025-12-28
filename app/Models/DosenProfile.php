<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DosenProfile extends Model
{
     protected $table = 'dosen_profiles';

    protected $fillable = [
        'user_id',
        'nidn',
        'prodi_id',
        'tanggal_masuk',
        'jabatan',
        'status_aktif',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Program Studi
     */
    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }
}
