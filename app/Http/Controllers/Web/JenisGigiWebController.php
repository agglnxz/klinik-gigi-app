<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisGigi;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JenisGigiWebController extends Controller
{
    public function index()
    {
        $jenis_gigi = JenisGigi::orderBy('id', 'desc')->get();
        $pendingHapus = \App\Models\PengajuanHapus::where('nama_tabel', 'jenis_gigi')
                        ->where('status_approval', 'Pending')
                        ->pluck('id_referensi')
                        ->toArray();
        return view('data_master.jenis_gigi.index', compact('jenis_gigi', 'pendingHapus'));
    }

    public function create()
    {
        return view('data_master.jenis_gigi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_gigi'      => 'required|string|unique:jenis_gigi,kode_gigi',
            'nama_jenis'     => 'required|string|max:100',
            'estimasi_biaya' => 'required|numeric|min:0',
        ]);

        JenisGigi::create([
            'kode_gigi'      => $request->kode_gigi,
            'nama_jenis'     => $request->nama_jenis,
            'estimasi_biaya' => $request->estimasi_biaya,
            'is_aktif'       => true,
        ]);

        return redirect()->route('jenis-gigi.index')->with('success', 'Jenis Gigi berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $jenisGigi = JenisGigi::findOrFail($id);
        return view('data_master.jenis_gigi.update', compact('jenisGigi'));
    }

    public function update(Request $request, int $id)
    {
        $jenisGigi = JenisGigi::findOrFail($id);

        $request->validate([
            'kode_gigi'      => 'required|string|unique:jenis_gigi,kode_gigi,'.$id,
            'nama_jenis'     => 'required|string|max:100',
            'estimasi_biaya' => 'required|numeric|min:0',
            'is_aktif'       => 'required|boolean',
        ]);

        $jenisGigi->update([
            'kode_gigi'      => $request->kode_gigi,
            'nama_jenis'     => $request->nama_jenis,
            'estimasi_biaya' => $request->estimasi_biaya,
            'is_aktif'       => $request->is_aktif,
        ]);

        return redirect()->route('jenis-gigi.index')->with('success', 'Jenis Gigi berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('jenis-gigi.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $jenisGigi = JenisGigi::findOrFail($id);
        $jenisGigi->delete(); // Soft Delete

        return redirect()->route('jenis-gigi.index')->with('success', 'Jenis Gigi berhasil dihapus!');
    }
}
