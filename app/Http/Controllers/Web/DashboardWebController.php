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

        $pesananProses = Pemesanan::where(
            'status_pemesanan',
            'Proses Lab'
        )->count();

        $pesananSelesai = Pemesanan::where(
            'status_pemesanan',
            'Sudah Dipasang'
        )->count();

        return view('dashboard', compact(
            'totalPasien',
            'pesananProses',
            'pesananSelesai'
        ));
    }
}