<?php

namespace Database\Factories;

use App\Models\MahasiswaProfile;
use App\Models\User;
use App\Models\Prodi;
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
        $faker = \Faker\Factory::create('id_ID');

       return [
            'user_id' => User::factory()->create(['role' => 'mahasiswa'])->id, // membuat user baru dengan role dosen
            'nim' => $faker->unique()->numerify('##########'), // 10 digit nomor unik
            'prodi_id' => Prodi::inRandomOrder()->first()->id, // pilih prodi random
            'semester' => $faker->numberBetween(1, 8),
            'tanggal_masuk' => $faker->date(),
            'status_aktif' => $faker->randomElement(['aktif', 'non-aktif']),
        ];
    }
}
