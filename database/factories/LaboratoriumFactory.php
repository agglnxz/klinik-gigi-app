<?php

namespace Database\Factories;

use App\Models\Laboratorium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Laboratorium>
 */
class LaboratoriumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_lab' => 'Lab Gigi ' . $this->faker->company(),
            'alamat'   => $this->faker->address(),
            'kontak'   => $this->faker->phoneNumber(),
            'is_aktif' => true,
        ];
    }
}
