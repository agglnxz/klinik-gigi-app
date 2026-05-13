<?php

namespace Database\Factories;

use App\Models\Asisten;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asisten>
 */
class AsistenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        'nama'     => $this->faker->name() . ', A.Md.Kes',
        'kontak'   => $this->faker->phoneNumber(),
        'is_aktif' => true,
        ];
    }
}
