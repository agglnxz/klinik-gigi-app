<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienWebController extends Controller
{
    public function index()
    {
        $pasien = Pasien::orderBy('id', 'desc')->get();
        return view('pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_rm'         => 'required|string|unique:pasien,no_rm',
            'nama'          => 'required|string|max:100',
            'kontak'        => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
        ]);

        Pasien::create([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'kontak'        => $request->kontak,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'is_aktif'      => true,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.update', compact('pasien'));
    }

    public function update(Request $request, int $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'no_rm'         => 'required|string|unique:pasien,no_rm,'.$id,
            'nama'          => 'required|string|max:100',
            'kontak'        => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'is_aktif'      => 'required|boolean',
        ]);

        $pasien->update([
            'no_rm'         => $request->no_rm,
            'nama'          => $request->nama,
            'kontak'        => $request->kontak,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'is_aktif'      => $request->is_aktif,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pasien.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $pasien = Pasien::findOrFail($id);
        $pasien->delete();

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil dihapus (Soft Delete)!');
    }
}
