<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokterWebController extends Controller
{
    public function index()
    {
        $dokter = Dokter::orderBy('id', 'desc')->get();
        return view('data_master.dokter.index', compact('dokter'));
    }

    public function create()
    {
        return view('data_master.dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'kontak' => 'required|string|max:20',
        ]);

        Dokter::create([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => true,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $Dokter = Dokter::findOrFail($id);
        return view('data_master.dokter.update', compact('Dokter'));
    }

    public function update(Request $request, int $id)
    {
        $Dokter = Dokter::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean',
        ]);

        $Dokter->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('dokter.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $Dokter = Dokter::findOrFail($id);
        $Dokter->delete(); // Soft Delete

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil dihapus!');
    }
}
