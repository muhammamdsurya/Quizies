<?php

namespace Database\Seeders;

use App\Models\DosenProfile;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DosenProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
    {
        // Ambil semua user dengan role dosen
        $dosenUsers = User::where('role', 'dosen')->get();

        foreach ($dosenUsers as $user) {
            DosenProfile::create([
                'user_id' => $user->id,
                'nidn' => fake()->unique()->numerify('##########'), // 10 digit
                'prodi_id' => Prodi::inRandomOrder()->first()->id,
                'jabatan' => fake()->randomElement(['Dosen', 'Asisten Dosen', 'Wali Kelas']),
                'tanggal_masuk' => fake()->date(),
                'status_aktif' => fake()->randomElement(['aktif', 'non-aktif']),
            ]);
        }
    }
}
