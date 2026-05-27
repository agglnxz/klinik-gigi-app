<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use App\Models\Notifikasi;
use Carbon\Carbon;

class GenerateNotifikasi extends Command
{
    /**
     * Nama dan signature perintah console.
     * Sekarang sinkron dengan perintah: php artisan notifikasi:generate
     *
     * @var string
     */
    protected $signature = 'notifikasi:generate';

    /**
     * Deskripsi perintah console.
     *
     * @var string
     */
    protected $description = 'Generate notifikasi berdasarkan estimasi selesai pesanan';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        $hariIni = Carbon::today();

        // Hanya cek pesanan yang masih dalam proses
        $pesananAktif = Pemesanan::with('pemeriksaan.pasien')
            ->where('status_pemesanan', 'dalam_proses')
            ->get();

        foreach ($pesananAktif as $p) {
            $estimasi = Carbon::parse($p->estimasi_selesai)->startOfDay();
            $selisihHari = $hariIni->diffInDays($estimasi, false); // false agar menghasilkan nilai minus saat terlambat

            $pesan = '';
            $status = '';

            // LOGIKA 1: Jika terlambat (selisih minus)
            if ($selisihHari < 0) {
                $telat = abs($selisihHari);
                $pesan = "terlambat {$telat} hari";
                $status = 'terlambat';
            }
            // LOGIKA 2: Jika segera tiba (H-3 sampai H-1)
            elseif ($selisihHari > 0 && $selisihHari <= 3) {
                $pesan = "akan segera tiba {$selisihHari} hari lagi";
                $status = 'segera_tiba';
            }
            // LOGIKA 3: Hari H
            elseif ($selisihHari == 0) {
                $pesan = "dijadwalkan tiba hari ini";
                $status = 'segera_tiba';
            }

            // Cegah duplikasi notifikasi yang sama untuk pesanan yang sama di hari yang sama
            if ($pesan !== '') {
                $sudahAda = Notifikasi::where('pemesanan_id', $p->id)
                    ->where('pesan', $pesan)
                    ->whereDate('created_at', $hariIni)
                    ->exists();

                if (!$sudahAda) {
                    Notifikasi::create([
                        'pemesanan_id' => $p->id,
                        'pesan'        => $pesan,
                        'status'       => $status,
                        'is_read'      => false
                    ]);
                }
            }
        }
    }
}
