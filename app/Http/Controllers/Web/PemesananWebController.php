<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Pemeriksaan;
use App\Models\Laboratorium;
use App\Models\JenisGigi;
use App\Models\PengajuanHapus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PemesananWebController extends Controller
{
    public function index(Request $request)
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. Total Pesanan khusus BULAN INI saja (Saran Mitra)
        $totalPesanan = Pemesanan::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        // 2. Sedang Diproses SEMUA PERIODE (Solusi Kebingungan Anda)
        // Menghitung semua yang belum selesai tanpa batas waktu agar tidak ada yang kelupaan
        $sedangDiproses = Pemesanan::whereIn('status_pemesanan', ['dalam_proses', 'tiba_di_klinik'])
            ->count();

        // 3. Selesai BULAN INI saja
        $pesananSelesai = Pemesanan::where('status_pemesanan', 'selesai')
            ->whereMonth('updated_at', $bulanIni) // dihitung berdasarkan kapan dia diselesaikan
            ->whereYear('updated_at', $tahunIni)
            ->count();

        // 4. Kueri Dasar dengan Eager Loading
        $query = Pemesanan::with(['pemeriksaan.pasien', 'lab', 'items.jenisGigi']);

        // 5. Logika Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pemesanan', 'like', "%{$search}%")
                    ->orWhereHas('pemeriksaan.pasien', function ($qp) use ($search) {
                        $qp->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('lab', function ($ql) use ($search) {
                        $ql->where('nama_lab', 'like', "%{$search}%");
                    });
            });
        }

        // 6. Logika Filter Periode Waktu (Tabel data utama)
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('tanggal_dikirim', Carbon::today());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('tanggal_dikirim', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('tanggal_dikirim', Carbon::now()->month)
                        ->whereYear('tanggal_dikirim', Carbon::now()->year);
                    break;
                case 'tahun_ini':
                    $query->whereYear('tanggal_dikirim', Carbon::now()->year);
                    break;
            }
        }

        // 7. Logika Pengurutan Data (Sorting)
        $sortOrder = $request->get('urutkan', 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $query->orderBy('tanggal_dikirim', $sortOrder);

        // Ambil data akhir setelah disaring
        $data = $query->paginate(10)->withQueryString();

        // 8. Ambil daftar ID pemesanan yang berstatus 'Pending' hapus
        $pendingHapus = PengajuanHapus::where('nama_tabel', 'pemesanan')
            ->where('status_approval', 'Pending')
            ->pluck('id_referensi')
            ->toArray();

        // Mengirimkan semua variabel yang dibutuhkan ke view
        return view('pemesanan.index', compact('data', 'totalPesanan', 'sedangDiproses', 'pesananSelesai', 'pendingHapus'));
    }

    public function show(int $id)
    {
        $data = Pemesanan::with(['pemeriksaan.pasien', 'lab', 'items.jenisGigi'])
            ->findOrFail($id);

        return view('pemesanan.show', compact('data'));
    }

    public function create()
    {
        // Logika Penomoran Otomatis: PSN-YYYYMMDD-NNN
        $datePrefix = now()->format('Ymd');
        $lastRecord = Pemesanan::where('no_pemesanan', 'like', 'PSN-' . $datePrefix . '-%')
            ->latest('id')
            ->first();

        $nextUrutan = $lastRecord ? ((int) substr($lastRecord->no_pemesanan, -3) + 1) : 1;
        $no_pemesanan = 'PSN-' . $datePrefix . '-' . str_pad($nextUrutan, 3, '0', STR_PAD_LEFT);

        // Ambil data master aktif untuk form
        $pemeriksaan = Pemeriksaan::with('pasien')->get();
        $lab         = Laboratorium::where('is_aktif', true)->get();
        $jenis_gigi  = JenisGigi::where('is_aktif', true)->get();

        return view('pemesanan.create', compact('no_pemesanan', 'pemeriksaan', 'lab', 'jenis_gigi'));
    }

    public function store(Request $request)
    {
        // Pengetatan validasi menggunakan aturan 'in' sebagai satpam lapis pertama
        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan,no_pemesanan',
            'id_pemeriksaan'   => 'required|exists:pemeriksaan,id',
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date',
            'biaya_lab'        => 'required|numeric',
            'harga_pasien'     => 'required|numeric',
            'status_bayar_lab' => 'required|in:belum_lunas,sudah_lunas',
            'status_pemesanan' => 'required|in:dalam_proses,tiba_di_klinik,dibatalkan,selesai',
            'items'            => 'required|array|min:1',
            'items.*'          => 'required|exists:jenis_gigi,id',
        ]);

        // Simpan induk pemesanan
        $pemesanan = Pemesanan::create($request->except('items'));

        // Simpan rincian banyak gigi ke tabel perantara (pivot)
        foreach ($request->items as $id_gigi) {
            PemesananItem::create([
                'id_pemesanan'  => $pemesanan->id,
                'id_jenis_gigi' => $id_gigi,
            ]);
        }

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil menyimpan pemesanan dengan multiple item gigi!');
    }

    public function edit(int $id)
    {
        return view('pemesanan.update', [
            'data'        => Pemesanan::with('items')->findOrFail($id),
            'pemeriksaan' => Pemeriksaan::all(),
            'lab'         => Laboratorium::where('is_aktif', true)->get(),
            'jenis_gigi'  => JenisGigi::where('is_aktif', true)->get()
        ]);
    }

    public function update(Request $request, int $id)
    {

        $pemesanan = Pemesanan::findOrFail($id);

        // Pengetatan validasi pembaruan data
        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan,no_pemesanan,' . $id,
            'id_pemeriksaan'   => 'required|exists:pemeriksaan,id',
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date',
            'biaya_lab'        => 'required|numeric',
            'harga_pasien'     => 'required|numeric',
            'status_bayar_lab' => 'required|in:belum_lunas,sudah_lunas',
            'status_pemesanan' => 'required|in:dalam_proses,tiba_di_klinik,dibatalkan,selesai',
            'items'            => 'required|array|min:1',
            'items.*'          => 'required|exists:jenis_gigi,id',
        ]);

        // Update data induk
        $pemesanan->update($request->except('items'));
        // ===== TAMBAHKAN LOGIKA INI =====
        // Jika status diubah menjadi selain 'dalam_proses', hapus semua notifikasi pesanan ini
        if (in_array($request->status_pemesanan, ['tiba_di_klinik', 'selesai', 'dibatalkan'])) {
            \App\Models\Notifikasi::where('pemesanan_id', $id)->delete();
        }

        // Reset dan perbarui daftar gigi di tabel pivot
        PemesananItem::where('id_pemesanan', $id)->delete();
        foreach ($request->items as $id_gigi) {
            PemesananItem::create([
                'id_pemesanan'  => $id,
                'id_jenis_gigi' => $id_gigi,
            ]);
        }

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil memperbarui data pemesanan dan item gigi!');
    }

    public function destroy(int $id)
    {
        // Menggunakan strtolower untuk pengamanan ekstra dari inkonsistensi huruf kapital
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        Pemesanan::findOrFail($id)->delete();

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil menghapus pesanan!');
    }

    public function pemesananRiwayat(request $request)
    {
        // 1. Kueri dasar dengan Eager Loading
        $query = Pemesanan::with(['pemeriksaan.pasien', 'lab'])
            ->whereIn('status_pemesanan', ['tiba_di_klinik', 'selesai']);

        // Papan pencarian sederhana (Opsional jika form pencarian disubmit)
        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('no_pemesanan', 'like', "%{$cari}%")
                    ->orWhereHas('pemeriksaan.pasien', function ($qp) use ($cari) {
                        $qp->where('nama', 'like', "%{$cari}%");
                    })
                    ->orWhereHas('lab', function ($ql) use ($cari) {
                        $ql->where('nama_lab', 'like', "%{$cari}%");
                    });
            });
        }

        // Ambil data dengan Pagination (misal 10 baris per halaman)
        $riwayat = $query->latest('updated_at')->paginate(10);

        // 2. Hitung statistik dinamis untuk Widget
        $totalPesanan     = Pemesanan::count();
        $telahTiba        = Pemesanan::where('status_pemesanan', 'tiba_di_klinik')->count();
        $pesananSelesai   = Pemesanan::where('status_pemesanan', 'selesai')->count();

        return view('riwayat_pemesanan.index', compact(
            'riwayat',
            'totalPesanan',
            'telahTiba',
            'pesananSelesai'
        ));
    }
}
