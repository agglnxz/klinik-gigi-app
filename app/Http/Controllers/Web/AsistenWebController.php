<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asisten;
use Illuminate\Http\Request;

class AsistenWebController extends Controller
{
    public function index()
    {
        $asisten = Asisten::orderBy('id', 'desc')->get();
        return view('asisten.index', compact('asisten'));
    }

    public function create()
    {
        return view('asisten.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'   => 'required|string|max:100',
            'kontak' => 'required|string|max:20',
        ]);

        Asisten::create([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => true,
        ]);

        return redirect()->route('asisten.index')->with('success', 'Data Asisten berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $asisten = Asisten::findOrFail($id);
        return view('asisten.edit', compact('asisten'));
    }

    public function update(Request $request, $id)
    {
        $asisten = Asisten::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean',
        ]);

        $asisten->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('asisten.index')->with('success', 'Data Asisten berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $asisten = Asisten::findOrFail($id);
        $asisten->delete(); // Soft Delete
        return redirect()->route('asisten.index')->with('success', 'Data Asisten berhasil dihapus!');
    }

    // GET: semua data yang dihapus
    public function semua()
    {
        $pasien = Asisten::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('pasien.semua', compact('pasien'));
    }

    // 8. RESTORE DATA PASIEN
    public function restore($id)
    {
        $pasien = Asisten::withTrashed()->findOrFail($id);

        $pasien->restore();
        $pasien->update(['is_aktif' => true]);

        return redirect()->route('pasien.semua')->with('success', 'Data Pasien berhasil direstore!');
    }
}
