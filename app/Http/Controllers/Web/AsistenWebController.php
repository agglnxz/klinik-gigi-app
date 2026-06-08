<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Asisten;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AsistenWebController extends Controller
{
    // 1. TAMPILKAN DAFTAR ASISTEN
    public function index()
    {
        $asisten = Asisten::orderBy('id', 'desc')->get();
        $pendingHapus = \App\Models\PengajuanHapus::where('nama_tabel', 'asisten')
                        ->where('status_approval', 'Pending')
                        ->pluck('id_referensi')
                        ->toArray();
        return view('data_master.asisten.index', compact('asisten', 'pendingHapus'));
    }

    // 2. FORM TAMBAH
    public function create()
    {
        return view('data_master.asisten.create');
    }

    // 3. SIMPAN DATABaru
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

        return redirect()->route('asisten.index')
            ->with('success', 'Data Asisten berhasil ditambahkan!');
    }

    // 4. FORM EDIT (Perbaikan: Gunakan Strict Type 'int')
    public function edit(int $id)
    {
        $asisten = Asisten::findOrFail($id);
        return view('data_master.asisten.update', compact('asisten'));
    }

    // 5. PROSES UPDATE (Perbaikan: Gunakan Strict Type 'int')
    public function update(Request $request, int $id)
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

        return redirect()->route('asisten.index')
            ->with('success', 'Data Asisten berhasil diperbarui!');
    }

    // 6. SOFT DELETE - KHUSUS DIREKTUR
    public function destroy(int $id)
    {
        // Proteksi RBAC: Tolak jika bukan direktur
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('asisten.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $asisten = Asisten::findOrFail($id);
        $asisten->delete(); // Soft Delete

        return redirect()->route('asisten.index')
            ->with('success', 'Data Asisten berhasil dihapus (Soft Delete) oleh Direktur!');
    }

    // -------------------------------------------------------------------------
    // FITUR TAMBAHAN (OPSIONAL UNTUK LAPORAN / DIREKTUR)
    // -------------------------------------------------------------------------

    // 7. TAMPILKAN DATA YANG SUDAH TER-SOFT DELETE
    // public function trash()
    // {
    //     $asisten = Asisten::onlyTrashed()->orderBy('id', 'desc')->get();
    //     return view('data_master.asisten.trash', compact('asisten'));
    // }

    // 8. KEMBALIKAN DATA (RESTORE) - KHUSUS DIREKTUR
    // public function restore(int $id)
    // {
    //     if (Auth::user()->role !== 'direktur') {
    //         abort(403, 'Akses Ditolak! Hanya Direktur yang berhak merestore data.');
    //     }

    //     $asisten = Asisten::withTrashed()->findOrFail($id);
    //     $asisten->restore();
    //     $asisten->update(['is_aktif' => true]);

    //     return redirect()->route('asisten.index')
    //         ->with('success', 'Data Asisten berhasil dikembalikan!');
    // }
}
