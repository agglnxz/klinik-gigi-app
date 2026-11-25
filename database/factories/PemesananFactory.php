<?php

namespace Database\Factories;

use App\Models\Pemesanan;
use App\Models\Pemeriksaan;
use App\Models\Laboratorium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pemesanan>
 */
class PemesananFactory extends Factory
{
    protected $model = Pemesanan::class;

    public function definition(): array
    {
        return [
            'no_pemesanan'        => 'PSN-' . date('Ymd') . '-' . $this->faker->unique()->numerify('####'),
            'id_pemeriksaan'      => Pemeriksaan::factory(),
            'harga_pasien'        => 500000,
            'diskon'              => 0,
            'biaya_pemasangan'    => 50000,
            'status_bayar_pasien' => 'belum_lunas',
            'status_booking'      => 'proses_lab',
        ];
    }
}
