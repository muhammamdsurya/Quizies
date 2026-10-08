<?php

namespace Database\Factories;

use App\Models\DosenProfile;
use App\Models\Prodi;
use App\Models\User;
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
        $faker = fake('id_ID'); // instance bersama agar unique() berlaku antar baris

        return [
            'user_id' => User::factory()->state(['role' => 'dosen']),
            'nidn' => $faker->unique()->numerify('##########'), // 10 digit nomor unik
            'prodi_id' => Prodi::inRandomOrder()->value('id') ?? Prodi::factory(),
            'tanggal_masuk' => $faker->date(),
            'jabatan' => $faker->randomElement(['Dosen', 'Kaprodi', 'Asisten Dosen']),
            'status_aktif' => $faker->randomElement(['aktif', 'non-aktif']),
        ];
    }
}
