<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DokterWebController extends Controller
{
    public function index()
    {
        $dokter = Dokter::orderBy('id', 'desc')->get();
        // 6. Data Pengajuan Hapus
        $pendingHapus = PengajuanHapus::where('nama_tabel', 'dokter')
            ->where('status_approval', 'Pending')
            ->pluck('id_referensi')->toArray();
        return view('data_master.dokter.index', compact('dokter', 'pendingHapus'));
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
        // Ubah $Dokter menjadi $dokter (huruf kecil)
        $dokter = Dokter::findOrFail($id);
        return view('data_master.dokter.update', compact('dokter'));
    }

    public function update(Request $request, int $id)
    {
        // Ubah $Dokter menjadi $dokter (huruf kecil) agar konsisten dan rapi
        $dokter = Dokter::findOrFail($id);

        $request->validate([
            'nama'     => 'required|string|max:100',
            'kontak'   => 'required|string|max:20',
            'is_aktif' => 'required|boolean',
        ]);

        $dokter->update([
            'nama'     => $request->nama,
            'kontak'   => $request->kontak,
            'is_aktif' => $request->is_aktif,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil diperbarui!');
    }

    public function destroy(Request $request, int $id)
    {
        // 1. Ambil data dokter yang mau diajukan hapus
        $dokter = Dokter::findOrFail($id);

        // 2. CEK APAKAH SUDAH ADA PENGAJUAN PENDING UNTUK DATA INI
        $sudahDiajukan = \App\Models\PengajuanHapus::where('nama_tabel', 'dokter')
                            ->where('id_referensi', $dokter->id)
                            ->where('status_approval', 'Pending')
                            ->exists();

        if ($sudahDiajukan) {
            return redirect()->route('dokter.index')
                ->with('error', 'Gagal! Data dokter ini sudah dalam proses pengajuan hapus dan sedang menunggu persetujuan Direktur.');
        }

        // 3. Validasi input alasan dari Admin jika lolos pengecekan di atas
        $request->validate([
            'alasan_hapus' => 'required|string|min:5',
        ]);

        // 4. Masukkan ke tabel pengajuan_hapus
        \App\Models\PengajuanHapus::create([
            'nama_tabel'      => 'dokter',
            'id_referensi'    => $dokter->id,
            'nama_data'       => $dokter->nama,
            'alasan_hapus'    => $request->alasan_hapus,
            'status_approval' => 'Pending',
            'id_pemohon'      => Auth::id(),
        ]);

        return redirect()->route('dokter.index')
            ->with('success', 'Permohonan penghapusan dokter berhasil dikirim ke Direktur.');
    }
}
