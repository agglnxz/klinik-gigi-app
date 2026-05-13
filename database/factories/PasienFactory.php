<?php

namespace Database\Factories;

use App\Models\Pasien;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pasien>
 */
class PasienFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'no_rm'         => 'RM-' . date('Y') . '-' . $this->faker->unique()->numerify('####'),
            'nama'          => $this->faker->name(),
            'kontak'        => $this->faker->phoneNumber(),
            'jenis_kelamin' => $this->faker->randomElement(['Laki-laki', 'Perempuan']),
            'alamat'        => $this->faker->address(),
            'is_aktif'      => true,
        ];
    }
}
