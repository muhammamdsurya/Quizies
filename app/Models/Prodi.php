<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    use HasFactory;

    protected $fillable = ['kode', 'nama'];

    public function mataKuliahs()
    {
        return $this->hasMany(MataKuliah::class);
    }

    public function dosenProfiles(): HasMany
    {
        return $this->hasMany(DosenProfile::class);
    }

    public function mahasiswaProfiles(): HasMany
    {
        return $this->hasMany(MahasiswaProfile::class);
    }
}
