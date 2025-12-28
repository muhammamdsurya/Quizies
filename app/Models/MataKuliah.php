<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataKuliah extends Model
{
    use HasFactory;

    protected $fillable = ['prodi_id', 'kode', 'semester', 'nama', 'sks'];

    public function prodi()
    {
        return $this->belongsTo(Prodi::class);
    }

    public function dosens()
    {
        return $this->belongsToMany(DosenProfile::class, 'dosen_mata_kuliah', 'mata_kuliah_id', 'dosen_id');
    }

    public function mahasiswas()
{
    return $this->belongsToMany(MahasiswaProfile::class, 'mahasiswa_mata_kuliah', 'mata_kuliah_id', 'mahasiswa_id');
}

}
