<?php

namespace Database\Factories;

use App\Models\JenisGigi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<JenisGigi>
 */
class JenisGigiFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_gigi'      => 'PRT-' . $this->faker->unique()->numerify('###'),
            'nama_jenis'     => $this->faker->words(3, true),
            'estimasi_biaya' => $this->faker->numberBetween(500000, 5000000),
            'is_aktif'       => true,
        ];
    }
}
