<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengajuanHapusWebController extends Controller
{
    public function index()
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('dashboard')
                ->with('error', 'Akses Ditolak! Khusus Direktur.');
        }

        $pengajuan = PengajuanHapus::with('pemohon')
            ->orderBy('created_at', 'desc')
            ->get();

        $menunggu = PengajuanHapus::where('status_approval', 'Pending')->count();

        $disetujui = PengajuanHapus::where('status_approval', 'Disetujui')->count();

        $ditolak = PengajuanHapus::where('status_approval', 'Ditolak')->count();

        return view('pengajuan_hapus.index', compact(
            'pengajuan',
            'menunggu',
            'disetujui',
            'ditolak'
        ));
    }

    public function store(Request $request)
    {
        // Kunci nama tabel yang diizinkan untuk diajukan (Anti-Hack Lapis 1)
        $tabelDiizinkan = ['pasien', 'pemesanan', 'pemeriksaan', 'dokter', 'asisten', 'laboratorium', 'jenis_gigi'];

        $request->validate([
            'nama_tabel'   => 'required|string|in:' . implode(',', $tabelDiizinkan),
            'id_referensi' => 'required|integer',
            'nama_data'    => 'required|string',
            'alasan_hapus' => 'required|string|min:5',
        ]);

        // Pastikan ID data aslinya memang valid & eksis di DB saat ini (Anti-Hack Lapis 2)
        $dataValid = DB::table($request->nama_tabel)->where('id', $request->id_referensi)->exists();

        if (!$dataValid) {
            return redirect()->back()->with('error', 'Aksi ilegal terdeteksi! Data tidak ditemukan.');
        }

        PengajuanHapus::create([
            'nama_tabel'      => $request->nama_tabel,
            'id_referensi'    => $request->id_referensi,
            'nama_data'       => $request->nama_data,
            'alasan_hapus'    => $request->alasan_hapus,
            'status_approval' => 'Pending',
            'id_pemohon'      => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Permohonan penghapusan data telah dikirim ke Direktur.');
    }

    public function approve($id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->back()->with('error', 'Akses Terbatas.');
        }

        $pengajuan = PengajuanHapus::findOrFail($id);
        if ($pengajuan->status_approval !== 'Pending') {
            return redirect()->back()->with('error', 'Sudah diproses.');
        }

        try {
            DB::table($pengajuan->nama_tabel)->where('id', $pengajuan->id_referensi)->delete();
            $pengajuan->update(['status_approval' => 'Disetujui']);

            return redirect()->back()->with('success', 'Data resmi dihapus dari sistem.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal mengeksekusi penghapusan. Data terikat relasi.');
        }
    }

    public function reject($id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->back()->with('error', 'Akses Terbatas.');
        }

        $pengajuan = PengajuanHapus::findOrFail($id);
        $pengajuan->update(['status_approval' => 'Ditolak']);

        return redirect()->back()->with('success', 'Pengajuan ditolak. Data aman.');
    }
}
