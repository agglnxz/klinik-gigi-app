<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use Illuminate\Http\Request;

class PasienWebController extends Controller
{
    // 1. TAMPILKAN DAFTAR PASIEN
    public function index()
    {
        $pasien = Pasien::orderBy('id', 'desc')->get();
        return view('pasien.index', compact('pasien'));
    }

    // 2. TAMPILKAN FORM TAMBAH PASIEN
    public function create()
    {
        return view('pasien.create');
    }

    // 3. PROSES SIMPAN DATA KE DATABASE
    public function store(Request $request)
    {
        // Validasi sesuai ERD asli
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
            'is_aktif'      => true, // Default pasien baru adalah aktif
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil ditambahkan!');
    }

    // 4. TAMPILKAN FORM EDIT
    public function edit($id)
    {
        $pasien = Pasien::findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    // 5. PROSES UPDATE DATA
    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);

        $request->validate([
            'no_rm'         => 'required|string|unique:pasien,no_rm,'.$id, // Abaikan validasi unique untuk diri sendiri
            'nama'          => 'required|string|max:100',
            'kontak'        => 'required|string|max:20',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'        => 'required|string',
            'is_aktif'      => 'required|boolean', // Admin bisa mengubah status aktif/nonaktif
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

    // 6. PROSES HAPUS DATA (SOFT DELETE)
    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        
        // Ajaibnya Laravel: Perintah delete() ini TIDAK akan menghapus data dari MySQL,
        // melainkan hanya akan mengisi kolom 'deleted_at' (Soft Delete)!
        $pasien->delete(); 

        return redirect()->route('pasien.index')->with('success', 'Data Pasien berhasil dihapus (Soft Delete)!');
    }

        // GET: semua data yang dihapus
    public function semua()
    {
        $pasien = Pasien::onlyTrashed()->orderBy('id', 'desc')->get();
        return view('pasien.semua', compact('pasien'));
    }

    // 8. RESTORE DATA PASIEN
    public function restore($id)
    {
        $pasien = Pasien::withTrashed()->findOrFail($id);

        $pasien->restore();
        $pasien->update(['is_aktif' => true]);

        return redirect()->route('pasien.semua')->with('success', 'Data Pasien berhasil direstore!');
    }
}
