<?php

namespace Database\Seeders;

use App\Models\MahasiswaProfile;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MahasiswaProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mahasiswaUsers = User::where('role', 'mahasiswa')->get();

    foreach ($mahasiswaUsers as $user) {
        // 1. Buat Profil Mahasiswa
        $prodiId = Prodi::inRandomOrder()->first()->id;
        $semesterSekarang = fake()->numberBetween(1, 8);

        $profile = MahasiswaProfile::create([
            'user_id' => $user->id,
            'nim' => fake()->unique()->numerify('##########'),
            'prodi_id' => $prodiId,
            'semester' => $semesterSekarang,
            'tanggal_masuk' => fake()->date(),
            'status_aktif' => 'aktif',
        ]);

        // 2. Ambil Mata Kuliah yang sesuai dengan Prodi dan Semester mahasiswa
        $mataKuliahIds = \App\Models\MataKuliah::where('prodi_id', $prodiId)
            ->inRandomOrder()
            ->limit(5) // Ambil 5 mata kuliah secara acak
            ->pluck('id');

        // 3. Masukkan ke tabel pivot (KRS)
        if ($mataKuliahIds->isNotEmpty()) {
            $profile->mataKuliahs()->attach($mataKuliahIds);
        }
    }
    }
}
