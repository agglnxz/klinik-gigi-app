<?php

namespace Database\Factories;

use App\Models\PesananLab;
use App\Models\Pemesanan;
use App\Models\Laboratorium;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PesananLab>
 */
class PesananLabFactory extends Factory
{
    protected $model = PesananLab::class;

    public function definition(): array
    {
        return [
            'id_pemesanan'     => Pemesanan::factory(),
            'id_lab'           => Laboratorium::factory(),
            'tanggal_dikirim'  => now()->toDateString(),
            'estimasi_selesai' => now()->addDays(5)->toDateString(),
            'tanggal_tiba'     => null,
            'biaya_lab'        => 150000,
            'status_bayar_lab' => 'belum_lunas',
        ];
    }
}

