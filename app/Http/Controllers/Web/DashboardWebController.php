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

        $laporanPesanan = Pemesanan::with(['pemeriksaan.pasien', 'lab'])
            ->whereIn('status_pemesanan', ['dalam_proses', 'tiba_di_klinik', 'selesai'])
            ->orderByDesc('tanggal_dikirim')
            ->get();

        $pesananBelumTiba = $laporanPesanan->where('status_pemesanan', 'dalam_proses')->count();
        $pesananSudahTiba = $laporanPesanan->whereIn('status_pemesanan', ['tiba_di_klinik', 'selesai'])->count();

        $pesananProses = Pemesanan::where(
            'status_pemesanan',
            'dalam_proses'
        )->count();

        $pesananSelesai = Pemesanan::where(
            'status_pemesanan',
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
