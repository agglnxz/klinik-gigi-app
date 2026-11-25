<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Pemesanan;
use App\Models\PesananLab;
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

        // Ambil pesanan lab yang sedang dalam pengerjaan (belum tiba di klinik)
        // dan pemesanan induknya masih aktif (belum dibatalkan/dihapus)
        $pesananLabAktif = PesananLab::with('pemesanan.pemeriksaan.pasien')
            ->whereNull('tanggal_tiba')
            ->whereHas('pemesanan', function ($q) {
                $q->whereIn('status_booking', ['proses_lab', 'pending']);
            })
            ->get();

        foreach ($pesananLabAktif as $lab) {
            $p = $lab->pemesanan;
            if (!$p) {
                continue;
            }

            $estimasi = Carbon::parse($lab->estimasi_selesai)->startOfDay();
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
