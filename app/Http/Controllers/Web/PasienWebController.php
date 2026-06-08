<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasienWebController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inisialisasi Query Dasar
        $query = Pasien::query();

        // 2. Logika Pencarian (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('no_rm', 'like', "%{$search}%")
                    ->orWhere('kontak', 'like', "%{$search}%");
            });
        }

        // 3. Logika Filter Periode
        if ($request->filled('periode') && $request->periode != 'Semua Waktu') {
            if ($request->periode == 'Hari Ini') $query->whereDate('created_at', today());
            if ($request->periode == 'Minggu Ini') $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
            if ($request->periode == 'Bulan Ini') $query->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year);
            if ($request->periode == 'Tahun Ini') $query->whereYear('created_at', now()->year);
        }

        // 4. Logika Urutkan (Sort)
        if ($request->filled('sort') && $request->sort == 'Tanggal Terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            $query->orderBy('id', 'desc'); // Default Tanggal Terbaru
        }

        $pasien = $query->paginate(10)->withQueryString();

        // 5. Hitung Widget Statistik Dinamis
        $totalPasien = Pasien::count();
        $pasienBaru = Pasien::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();
        $kunjunganHariIni = Pemeriksaan::whereDate('tanggal', today())->count();

        // 6. Data Pengajuan Hapus
        $pendingHapus = PengajuanHapus::where('nama_tabel', 'pasien')
            ->where('status_approval', 'Pending')
            ->pluck('id_referensi')->toArray();

        // Kirim semua variabel ke halaman View
        return view('pasien.index', compact(
            'pasien',
            'pendingHapus',
            'totalPasien',
            'pasienBaru',
            'kunjunganHariIni'
        ));
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
            'no_rm'         => 'required|string|unique:pasien,no_rm,' . $id,
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
