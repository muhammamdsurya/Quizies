<?php

namespace Database\Factories;

use App\Models\DosenProfile;
use App\Models\User;
use App\Models\Prodi;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DosenProfile>
 */
class DosenProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DosenProfile::class;

    public function definition(): array
    {
        $faker = \Faker\Factory::create('id_ID');

         return [
            'user_id' => User::factory()->create(['role' => 'dosen'])->id, // membuat user baru dengan role dosen
            'nidn' => $faker->unique()->numerify('##########'), // 10 digit nomor unik
            'prodi_id' => Prodi::inRandomOrder()->first()->id, // pilih prodi random
            'tanggal_masuk' => $faker->date(),
            'jabatan' => $faker->randomElement(['Dosen', 'Kaprodi', 'Asisten Dosen']),
            'status_aktif' => $faker->randomElement(['aktif', 'non-aktif']),
        ];
    }
}
