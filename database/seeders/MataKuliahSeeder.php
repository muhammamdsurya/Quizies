<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Prodi;
use App\Models\DosenProfile;
use Illuminate\Database\Seeder;

class MataKuliahSeeder extends Seeder
{

public function run(): void
    {
        // Ambil semua ID dosen yang tersedia di database
        $dosenIds = DosenProfile::pluck('id');

        if ($dosenIds->isEmpty()) {
            $this->command->warn("Tidak ada data dosen. Pastikan DosenSeeder dijalankan lebih dulu!");
            return;
        }

        Prodi::all()->each(function ($prodi) use ($dosenIds) {
            $usedCombinations = [];

            for ($i = 1; $i <= 5; $i++) {
                do {
                    $semester = fake()->numberBetween(1, 8);
                    $sks = fake()->randomElement([2, 3]);
                    $combo = $semester . '-' . $sks;
                } while (in_array($combo, $usedCombinations));

                $usedCombinations[] = $combo;

                // 1. Buat record Mata Kuliah
                $mk = MataKuliah::create([
                    'prodi_id' => $prodi->id,
                    'nama' => fake()->words(3, true),
                    'sks' => $sks,
                    'semester' => $semester,
                    'kode' => $prodi->kode
                        . '-' . str_pad($semester, 2, '0', STR_PAD_LEFT)
                        . '-' . $sks,
                ]);

                // 2. Lampirkan dosen ke tabel pivot (dosen_mata_kuliah)
                // Mengambil 1 atau 2 dosen secara acak
                $randomDosen = $dosenIds->random(rand(1, 2));
                $mk->dosens()->attach($randomDosen);
            }
        });
    }
}
