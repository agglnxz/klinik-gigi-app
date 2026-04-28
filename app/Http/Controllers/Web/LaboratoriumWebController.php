<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use Illuminate\Http\Request;

class LaboratoriumWebController extends Controller
{
    public function index()
    {
        $laboratorium = Laboratorium::orderBy('id', 'desc')->get();
        return view('laboratorium.index', compact('laboratorium'));
    }

    public function create()
    {
        return view('laboratorium.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_lab' => 'required|string|max:100',
            'alamat'   => 'required|string',
            'kontak'   => 'required|string|max:20',
        ]);

        Laboratorium::create([
            'nama_lab' => $request->nama_lab,
            'alamat'   => $request->alamat,
            'kontak'   => $request->kontak,
            'is_aktif' => true,
        ]);

        return redirect()->route('laboratorium.index')->with('success', 'Data Laboratorium berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        return view('laboratorium.edit', compact('laboratorium'));
    }

    public function update(Request $request, $id)
    {
        $laboratorium = Laboratorium::findOrFail($id);

        $request->validate([
            'nama_lab' => 'required|string|max:100',
            'alamat'   => 'required|string',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean',
        ]);

        $laboratorium->update([
            'nama_lab' => $request->nama_lab,
            'alamat'   => $request->alamat,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('laboratorium.index')->with('success', 'Data Laboratorium berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        $laboratorium->delete(); // Soft Delete
        return redirect()->route('laboratorium.index')->with('success', 'Data Laboratorium berhasil dihapus!');
    }

        // GET: semua data yang dihapus
    public function semua()
    {
        $pasien = Laboratorium::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('pasien.semua', compact('pasien'));
    }

    // 8. RESTORE DATA PASIEN
    public function restore($id)
    {
        $pasien = Laboratorium::withTrashed()->findOrFail($id);

        $pasien->restore();
        $pasien->update(['is_aktif' => true]);

        return redirect()->route('pasien.semua')->with('success', 'Data Pasien berhasil direstore!');
    }
}
