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
            'no_pemesanan'     => 'PSN-' . date('Ymd') . '-' . $this->faker->unique()->numerify('####'),
            'id_pemeriksaan'   => Pemeriksaan::factory(),
            'id_lab'           => Laboratorium::factory(),
            'tanggal_dikirim'  => now()->toDateString(),
            'estimasi_selesai' => now()->addDays(5)->toDateString(),
            'biaya_lab'        => 150000,
            'harga_pasien'     => 500000,
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'diskon'           => 0,
        ];
    }
}
