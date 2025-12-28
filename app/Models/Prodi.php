<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prodi extends Model
{
    protected $fillable = ['kode', 'nama'];

    public function dosenProfiles(): HasMany
    {
        return $this->hasMany(DosenProfile::class);
    }

    public function mahasiswaProfiles(): HasMany
    {
        return $this->hasMany(MahasiswaProfile::class);
    }
}
