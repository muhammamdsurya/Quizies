<?php

namespace Database\Seeders;

use App\Models\User; // ⬅️ WAJIB
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Domain .test tidak pernah menerima email sungguhan, jadi kode OTP tidak bocor ke orang lain.
        // Akun Kaprodi memakai SEED_KAPRODI_EMAIL agar bisa diisi email asli untuk menerima OTP.

        // 1. Akun KAPRODI
        User::create([
            'name' => 'Kaprodi User',
            'email' => env('SEED_KAPRODI_EMAIL') ?: 'kaprodi@kuiz.test',
            'password' => Hash::make('password123'),
            'role' => 'kaprodi',
        ]);

        // 2. Akun DOSEN
        User::create([
            'name' => 'Dosen User',
            'email' => 'dosen@kuiz.test',
            'password' => Hash::make('password123'),
            'role' => 'dosen',
        ]);

        // 3. Akun MAHASISWA
        User::create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@kuiz.test',
            'password' => Hash::make('password123'),
            'role' => 'mahasiswa',
        ]);

        User::factory()->count(20)->create([
            'role' => fn () => fake()->randomElement(['dosen', 'mahasiswa']),
        ]);
    }
}
