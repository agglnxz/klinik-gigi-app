<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaboratoriumWebController extends Controller
{
    public function index()
    {
        $laboratorium = Laboratorium::orderBy('id', 'desc')->get();
        return view('data_master.laboratorium.index', compact('laboratorium'));
    }

    public function create()
    {
        return view('data_master.laboratorium.create');
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

    public function edit(int $id)
    {
        $laboratorium = Laboratorium::findOrFail($id);
        return view('data_master.laboratorium.update', compact('laboratorium'));
    }

    public function update(Request $request, int $id)
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

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('laboratorium.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $laboratorium = Laboratorium::findOrFail($id);
        $laboratorium->delete(); // Soft Delete

        return redirect()->route('laboratorium.index')->with('success', 'Data Laboratorium berhasil dihapus!');
    }
}
