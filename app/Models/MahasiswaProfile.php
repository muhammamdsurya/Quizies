<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MahasiswaProfile extends Model
{
    use HasFactory;

     protected $fillable = [
        'user_id',
        'nim',
        'prodi_id',
        'semester',
        'tanggal_masuk',
        'status_aktif',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

     public function mataKuliahs()
{
    return $this->belongsToMany(MataKuliah::class, 'mahasiswa_mata_kuliah', 'mahasiswa_id', 'mata_kuliah_id');
}

}
