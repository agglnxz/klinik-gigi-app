<?php

namespace Database\Factories;

use App\Models\PengajuanHapus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PengajuanHapus>
 */
class PengajuanHapusFactory extends Factory
{
    protected $model = PengajuanHapus::class;

    public function definition(): array
    {
        return [
            'nama_tabel'      => 'pasien',
            'id_referensi'    => 1,
            'nama_data'       => $this->faker->name(),
            'alasan_hapus'    => 'Alasan penghapusan untuk pengujian unit',
            'status_approval' => 'Pending',
            'id_pemohon'      => User::factory(),
        ];
    }
}
