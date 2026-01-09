<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JawabanMahasiswa extends Model
{
    protected $fillable = ['ujian_attempt_id', 'detail_soal_id', 'jawaban', 'is_benar'];
}
