<?php

namespace Database\Factories;

use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Notifikasi>
 */
class NotifikasiFactory extends Factory
{
    protected $model = Notifikasi::class;

    public function definition(): array
    {
        return [
            'pemesanan_id' => Pemesanan::factory(),
            'pesan'        => 'Notifikasi pengujian unit',
            'status'       => 'segera_tiba',
            'is_read'      => false,
        ];
    }
}
