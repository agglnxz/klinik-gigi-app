<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemeriksaan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\Asisten;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemeriksaanWebController extends Controller
{
public function index(Request $request)
    {
        // 1. Inisialisasi Query Dasar dengan Eager Loading
        $query = Pemeriksaan::with(['pasien', 'dokter', 'asisten']);

        // 2. Logika Pencarian (Mencari No Pemeriksaan, Catatan, Nama Pasien, atau Nama Dokter)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pemeriksaan', 'like', "%{$search}%")
                  ->orWhere('catatan', 'like', "%{$search}%")
                  ->orWhereHas('pasien', function ($qp) use ($search) {
                      $qp->where('nama', 'like', "%{$search}%");
                  })
                  ->orWhereHas('dokter', function ($qd) use ($search) {
                      $qd->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        // 3. Logika Filter Periode (Berdasarkan kolom tanggal pemeriksaan)
        if ($request->filled('periode') && $request->periode != 'Semua Waktu') {
            if ($request->periode == 'Hari Ini') $query->whereDate('tanggal', today());
            if ($request->periode == 'Minggu Ini') $query->whereBetween('tanggal', [now()->startOfWeek(), now()->endOfWeek()]);
            if ($request->periode == 'Bulan Ini') $query->whereMonth('tanggal', now()->month)->whereYear('tanggal', now()->year);
            if ($request->periode == 'Tahun Ini') $query->whereYear('tanggal', now()->year);
        }

        // 4. Logika Urutkan (Sort)
        if ($request->filled('sort') && $request->sort == 'Tanggal Terlama') {
            $query->orderBy('tanggal', 'asc');
        } else {
            $query->orderBy('tanggal', 'desc'); // Default Tanggal Terbaru
        }


        $pemeriksaan = $query->paginate(10)->withQueryString();

        // 6. Hitung statistik widget secara dinamis (Tetap Global)
        $totalPasien = Pasien::count();
        $pasienBaru  = Pasien::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        $kunjunganHariIni = Pemeriksaan::whereDate('tanggal', today())->count();

        // 7. Ambil daftar ID pemeriksaan yang berstatus 'Pending' hapus
        $pendingHapus = PengajuanHapus::where('nama_tabel', 'pemeriksaan')
            ->where('status_approval', 'Pending')
            ->pluck('id_referensi')
            ->toArray();

        return view('pemeriksaan.index', compact(
            'pemeriksaan',
            'totalPasien',
            'pasienBaru',
            'kunjunganHariIni',
            'pendingHapus'
        ));
    }

    public function create()
    {
        // 1. Dapatkan tanggal hari ini dengan format YYYYMMDD (Contoh: 20260512)
        $datePrefix = now()->format('Ymd');

        // 2. Cari data pemeriksaan terakhir khusus HARI INI
        $lastRecord = Pemeriksaan::where('no_pemeriksaan', 'like', 'PRX-' . $datePrefix . '-%')
            ->latest('id')
            ->first();

        // 3. Jika ada transaksi hari ini, ambil 3 angka terakhir lalu tambah 1. Jika kosong, mulai dari 1.
        $nextUrutan = $lastRecord ? ((int) substr($lastRecord->no_pemeriksaan, -3) + 1) : 1;

        // 4. Rangkai format baru (Hasil: PRX-20260512-001, PRX-20260512-002, dst.)
        $no_pemeriksaan = 'PRX-' . $datePrefix . '-' . str_pad($nextUrutan, 3, '0', STR_PAD_LEFT);

        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.create', compact('no_pemeriksaan', 'pasien', 'dokter', 'asisten'));
    }

    public function store(Request $request)
    {
        // 1. Validasi dengan nama parameter yang baru & seragam
        $request->validate([
            'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan',
            'tanggal'        => 'required|date',
            'catatan'        => 'required|string',
            'id_pasien'      => 'required|exists:pasien,id',
            'id_dokter'      => 'required|exists:dokter,id',
            'id_asisten'     => 'nullable|exists:asisten,id',
        ]);

        // 2. Eksekusi simpan ke database
        Pemeriksaan::create([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal,
            'catatan'        => $request->catatan,
            'id_pasien'      => $request->id_pasien,
            'id_dokter'      => $request->id_dokter,
            'id_asisten'     => $request->id_asisten,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil ditambahkan!');
    }

    public function edit(int $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        $pasien = Pasien::where('is_aktif', true)->get();
        $dokter = Dokter::where('is_aktif', true)->get();
        $asisten = Asisten::where('is_aktif', true)->get();

        return view('pemeriksaan.update', compact(
            'pemeriksaan',
            'pasien',
            'dokter',
            'asisten'
        ));
    }

    public function update(Request $request, int $id)
    {
        $pemeriksaan = Pemeriksaan::findOrFail($id);

        // 1. Validasi disesuaikan dengan 'name' terbaru di update.blade.php
        $request->validate([
            'no_pemeriksaan' => 'required|string|unique:pemeriksaan,no_pemeriksaan,' . $id,
            'tanggal'        => 'required|date',
            'catatan'        => 'required|string',
            'id_pasien'      => 'required|exists:pasien,id',
            'id_dokter'      => 'required|exists:dokter,id',
            'id_asisten'     => 'nullable|exists:asisten,id',
        ]);

        // 2. Eksekusi update menggunakan input yang benar
        $pemeriksaan->update([
            'no_pemeriksaan' => $request->no_pemeriksaan,
            'tanggal'        => $request->tanggal,
            'catatan'        => $request->catatan,
            'id_pasien'      => $request->id_pasien,
            'id_dokter'      => $request->id_dokter,
            'id_asisten'     => $request->id_asisten,
        ]);

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil diperbarui!');
    }

    public function destroy(int $id)
    {
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pemeriksaan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        $pemeriksaan = Pemeriksaan::findOrFail($id);
        $pemeriksaan->delete();

        return redirect()->route('pemeriksaan.index')
            ->with('success', 'Data Pemeriksaan berhasil dihapus!');
    }

    // public function semua()
    // {
    //     $data = Pemeriksaan::onlyTrashed()->get();
    //     return view('pemeriksaan.semua', compact('data'));
    // }

    // public function restore(int $id)
    // {
    //     if (Auth::user()->role !== 'direktur') {
    //         return redirect()->route('pemeriksaan.semua')
    //             ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak merestore data.');
    //     }

    //     Pemeriksaan::withTrashed()->findOrFail($id)->restore();

    //     return redirect()->route('pemeriksaan.semua')
    //         ->with('success', 'Berhasil restore');
    // }
}
