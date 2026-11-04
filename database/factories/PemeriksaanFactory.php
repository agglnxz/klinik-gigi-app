<?php

namespace Database\Factories;

use App\Models\Pemeriksaan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Asisten;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pemeriksaan>
 */
class PemeriksaanFactory extends Factory
{
    protected $model = Pemeriksaan::class;

    public function definition(): array
    {
        return [
            'no_pemeriksaan' => 'PRX-' . date('Ymd') . '-' . $this->faker->unique()->numerify('####'),
            'tanggal'        => now()->toDateString(),
            'catatan'        => $this->faker->sentence(),
            'id_pasien'      => Pasien::factory(),
            'id_dokter'      => Dokter::factory(),
            'id_asisten'     => Asisten::factory(),
        ];
    }
}
