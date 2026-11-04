<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\Pemesanan;

class DashboardWebController extends Controller
{
    public function dashboard()
    {
        $totalPasien = Pasien::count();

        // Mengambil data pemesanan beserta relasi pemeriksaan, pasien, dan detail pesanan lab
        $laporanPesanan = Pemesanan::with(['pemeriksaan.pasien', 'pesananLab.lab'])
            ->whereIn('status_booking', ['proses_lab', 'siap_pasang', 'selesai'])
            ->orderByDesc('created_at') // Menggunakan created_at karena tanggal_dikirim pindah ke pesanan_lab
            ->get();

        // Hitung pesanan yang giginya belum tiba dari lab
        $pesananBelumTiba = $laporanPesanan->where('status_booking', 'proses_lab')->count();
        
        // Hitung pesanan yang giginya sudah tiba dan siap pasang, atau sudah selesai
        $pesananSudahTiba = $laporanPesanan->whereIn('status_booking', ['siap_pasang', 'selesai'])->count();

        // Hitung seluruh pesanan yang belum selesai (pending, proses ke lab, atau siap pasang)
        $pesananProses = Pemesanan::whereIn(
            'status_booking',
            ['pending', 'proses_lab', 'siap_pasang']
        )->count();

        $pesananSelesai = Pemesanan::where(
            'status_booking',
            'selesai'
        )->count();

        return view('dashboard', compact(
            'totalPasien',
            'pesananProses',
            'pesananSelesai',
            'laporanPesanan',
            'pesananBelumTiba',
            'pesananSudahTiba'
        ));
    }
}