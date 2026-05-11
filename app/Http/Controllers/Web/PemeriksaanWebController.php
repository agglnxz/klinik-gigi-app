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

        return view('pemeriksaan.index', compact('pemeriksaan'));
    }

    public function create()
    {
        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.create', compact('pasien', 'dokter', 'asisten'));
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

    public function semua()
    {
        $data = Pemeriksaan::onlyTrashed()->get();
        return view('pemeriksaan.semua', compact('data'));
    }

    public function restore(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pemeriksaan.semua')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak merestore data.');
        }

        Pemeriksaan::withTrashed()->findOrFail($id)->restore();

        return redirect()->route('pemeriksaan.semua')
            ->with('success', 'Berhasil restore');
    }
}
