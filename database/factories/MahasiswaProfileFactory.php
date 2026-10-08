<?php

namespace Database\Factories;

use App\Models\MahasiswaProfile;
use App\Models\Prodi;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MahasiswaProfile>
 */
class MahasiswaProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = MahasiswaProfile::class;

    public function definition(): array
    {
        $faker = fake('id_ID'); // instance bersama agar unique() berlaku antar baris

        return [
            'user_id' => User::factory()->state(['role' => 'mahasiswa']),
            'nim' => $faker->unique()->numerify('##########'), // 10 digit nomor unik
            'prodi_id' => Prodi::inRandomOrder()->value('id') ?? Prodi::factory(),
            'semester' => $faker->numberBetween(1, 8),
            'tanggal_masuk' => $faker->date(),
            'status_aktif' => $faker->randomElement(['aktif', 'non-aktif']),
        ];
    }
}
