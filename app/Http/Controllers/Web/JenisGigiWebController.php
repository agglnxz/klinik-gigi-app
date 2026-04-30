<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\JenisGigi;
use Illuminate\Http\Request;

class JenisGigiWebController extends Controller
{
    public function index()
    {
        $jenis_gigi = JenisGigi::orderBy('id', 'desc')->get();
        return view('jenis_gigi.index', compact('jenis_gigi'));
    }

    public function create()
    {
        return view('jenis_gigi.create');
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

    public function edit($id)
    {
        $jenisGigi = JenisGigi::findOrFail($id);
        return view('jenis_gigi.edit', compact('jenisGigi'));
    }

    public function update(Request $request, $id)
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

    public function destroy($id)
    {
        $jenisGigi = JenisGigi::findOrFail($id);
        $jenisGigi->delete(); // Soft Delete
        return redirect()->route('jenis-gigi.index')->with('success', 'Jenis Gigi berhasil dihapus!');
    }

    // GET: semua data yang dihapus
    public function semua()
    {
        $pasien = JenisGigi::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('pasien.semua', compact('pasien'));
    }

    // 8. RESTORE DATA PASIEN
    public function restore($id)
    {
        $pasien = JenisGigi::withTrashed()->findOrFail($id);

        $pasien->restore();
        $pasien->update(['is_aktif' => true]);

        return redirect()->route('pasien.semua')->with('success', 'Data Pasien berhasil direstore!');
    }
}
