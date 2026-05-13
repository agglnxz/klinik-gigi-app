<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Asisten;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanWebController extends Controller
{
    public function index()
    {
        $pemeriksaan = Pemeriksaan::with(['pasien', 'dokter', 'asisten'])
            ->orderBy('id', 'desc')
            ->get();
        // 2. Hitung statistik widget secara dinamis
        $totalPasien = Pasien::count();

        $pasienBaru  = Pasien::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $kunjunganHariIni = Pemeriksaan::whereDate('tanggal', now()->toDateString())->count();

        return view('pemeriksaan.index', compact(
            'pemeriksaan',
            'totalPasien',
            'pasienBaru',
            'kunjunganHariIni'
        ));
    }

    public function create()
    {
        // 1. Dapatkan tanggal hari ini dengan format YYYYMMDD (Contoh: 20260512)
        $datePrefix = now()->format('Ymd');

        // 2. Cari data pemeriksaan terakhir khusus HARI INI
        $lastRecord = Pemeriksaan::where('no_pemeriksaan', 'like', 'PRX-' . $datePrefix . '-%')
            ->latest('id')
            ->first();

        // 3. Jika ada transaksi hari ini, ambil 3 angka terakhir lalu tambah 1. Jika kosong, mulai dari 1.
        $nextUrutan = $lastRecord ? ((int) substr($lastRecord->no_pemeriksaan, -3) + 1) : 1;

        // 4. Rangkai format baru (Hasil: PRX-20260512-001, PRX-20260512-002, dst.)
        $no_pemeriksaan = 'PRX-' . $datePrefix . '-' . str_pad($nextUrutan, 3, '0', STR_PAD_LEFT);

        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.create', compact('no_pemeriksaan', 'pasien', 'dokter', 'asisten'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemeriksaan'      => 'required|string|unique:pemeriksaan,no_pemeriksaan',
            'tanggal_pemeriksaan' => 'required|date',
            'catatan_klinis'      => 'required|string',
            'pasien'              => 'required|exists:pasien,id',
            'dokter_gigi'         => 'required|exists:dokter,id',
            'asisten_dokter'      => 'nullable|exists:asisten,id',
        ]);

        Pemeriksaan::create([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal_pemeriksaan,
            'catatan'        => $request->catatan_klinis,
            'id_pasien'      => $request->pasien,
            'id_dokter'      => $request->dokter_gigi,
            'id_asisten'     => $request->asisten_dokter,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil disimpan!');
    }

    public function edit(int $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.edit', compact(
            'pemeriksaan',
            'pasien',
            'dokter',
            'asisten'
        ));
    }

    public function update(Request $request, int $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $request->validate([
            'no_pemeriksaan'      => 'required|string|unique:pemeriksaan,no_pemeriksaan,' . $id,
            'tanggal_pemeriksaan' => 'required|date',
            'catatan_klinis'      => 'required|string',
            'pasien'              => 'required|exists:pasien,id',
            'dokter_gigi'         => 'required|exists:dokter,id',
            'asisten_dokter'      => 'nullable|exists:asisten,id',
        ]);

        $pemeriksaan->update([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal_pemeriksaan,
            'catatan'        => $request->catatan_klinis,
            'id_pasien'      => $request->pasien,
            'id_dokter'      => $request->dokter_gigi,
            'id_asisten'     => $request->asisten_dokter,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pemeriksaan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil dihapus!');
    }

    // public function semua()
    // {
    //     $data = Pemeriksaan::onlyTrashed()->get();
    //     return view('pemeriksaan.semua', compact('data'));
    // }

    // public function restore(int $id)
    // {
    //     if (Auth::user()->role !== 'direktur') {
    //         return redirect()->route('pemeriksaan.semua')
    //             ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak merestore data.');
    //     }

    //     Pemeriksaan::withTrashed()->findOrFail($id)->restore();

    //     return redirect()->route('pemeriksaan.semua')
    //         ->with('success', 'Berhasil restore');
    // }
}
