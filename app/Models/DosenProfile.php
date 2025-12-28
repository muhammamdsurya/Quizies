<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DosenProfile extends Model
{
    use HasFactory;

    protected $table = 'dosen_profiles';

    protected $fillable = ['user_id', 'nidn', 'jabatan','prodi_id', 'tanggal_masuk', 'status_aktif'];

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

    public function mataKuliahs()
    {
        return $this->belongsToMany(MataKuliah::class, 'dosen_mata_kuliah', 'dosen_id', 'mata_kuliah_id');

    }

}
