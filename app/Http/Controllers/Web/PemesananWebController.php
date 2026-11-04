<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Pemeriksaan;
use App\Models\JenisGigi;
use App\Models\PengajuanHapus;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class PemesananWebController extends Controller
{
    /**
     * Menampilkan daftar transaksi & booking pasien.
     */
    public function index(Request $request)
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;

        // 1. Total Booking Pasien khusus BULAN INI saja
        $totalPesanan = Pemesanan::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->count();

        // 2. Sedang Diproses SEMUA PERIODE (Status booking yang belum 'selesai')
        $sedangDiproses = Pemesanan::whereIn('status_booking', ['pending', 'proses_lab', 'siap_pasang'])
            ->count();

        // 3. Selesai BULAN INI saja
        $pesananSelesai = Pemesanan::where('status_booking', 'selesai')
            ->whereMonth('updated_at', $bulanIni)
            ->whereYear('updated_at', $tahunIni)
            ->count();

        // 4. Kueri Dasar dengan Eager Loading
        $query = Pemesanan::with(['pemeriksaan.pasien', 'pesananLab.lab', 'items.jenisGigi']);

        // 5. Logika Filter Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_pemesanan', 'like', "%{$search}%")
                    ->orWhereHas('pemeriksaan.pasien', function ($qp) use ($search) {
                        $qp->where('nama', 'like', "%{$search}%");
                    })
                    ->orWhereHas('pesananLab.lab', function ($ql) use ($search) {
                        $ql->where('nama_lab', 'like', "%{$search}%");
                    });
            });
        }

        // 6. Logika Filter Periode Waktu Booking Pasien
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'hari_ini':
                    $query->whereDate('created_at', Carbon::today());
                    break;
                case 'minggu_ini':
                    $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()]);
                    break;
                case 'bulan_ini':
                    $query->whereMonth('created_at', Carbon::now()->month)
                        ->whereYear('created_at', Carbon::now()->year);
                    break;
                case 'tahun_ini':
                    $query->whereYear('created_at', Carbon::now()->year);
                    break;
            }
        }

        // 7. Pengurutan Data
        $sortOrder = $request->get('urutkan', 'terbaru') === 'terlama' ? 'asc' : 'desc';
        $query->orderBy('created_at', $sortOrder);

        $data = $query->paginate(10)->withQueryString();

        // 8. Ambil daftar ID pemesanan yang berstatus 'Pending' hapus
        $pendingHapus = PengajuanHapus::where('nama_tabel', 'pemesanan')
            ->where('status_approval', 'Pending')
            ->pluck('id_referensi')
            ->toArray();

        return view('pemesanan.index', compact(
            'data',
            'totalPesanan',
            'sedangDiproses',
            'pesananSelesai',
            'pendingHapus'
        ));
    }

    /**
     * Menampilkan detail booking pasien.
     */
    public function show(int $id)
    {
        $data = Pemesanan::with([
            'pemeriksaan.pasien',
            'pemeriksaan.dokter',
            'pesananLab.lab',
            'items.jenisGigi'
        ])->findOrFail($id);

        return view('pemesanan.show', compact('data'));
    }

    /**
     * Menampilkan invoice resmi untuk pasien.
     * Murni data transaksi pasien tanpa mengekspos biaya & operasional lab.
     */
    public function invoice(int $id)
    {
        $data = Pemesanan::with([
            'pemeriksaan.pasien',
            'pemeriksaan.dokter',
            'items.jenisGigi',
        ])->findOrFail($id);

        $groupedItems = $data->items->groupBy(fn($i) => $i->jenisGigi->nama_jenis ?? 'Item Terhapus');

        $totalItemGigi   = (float) $data->items->sum('harga_jual_pasien');
        $biayaPemasangan = (float) $data->biaya_pemasangan;
        $diskon          = (float) $data->diskon;
        $totalTagihan    = (float) $data->total_tagihan_pasien;

        return view('pemesanan.invoice', compact(
            'data',
            'groupedItems',
            'totalItemGigi',
            'biayaPemasangan',
            'diskon',
            'totalTagihan'
        ));
    }

    /**
     * Form pendaftaran booking pemesanan baru pasien.
     */
    public function create()
    {
        // Penomoran Otomatis: PSN-YYYYMMDD-NNN
        $datePrefix = now()->format('Ymd');
        $lastRecord = Pemesanan::where('no_pemesanan', 'like', 'PSN-' . $datePrefix . '-%')
            ->latest('id')
            ->first();

        $nextUrutan = $lastRecord ? ((int) substr($lastRecord->no_pemesanan, -3) + 1) : 1;
        $no_pemesanan = 'PSN-' . $datePrefix . '-' . str_pad($nextUrutan, 3, '0', STR_PAD_LEFT);

        $pemeriksaan = Pemeriksaan::with('pasien')->get();
        $jenis_gigi  = JenisGigi::where('is_aktif', true)->get();

        return view('pemesanan.create', compact('no_pemesanan', 'pemeriksaan', 'jenis_gigi'));
    }

    /**
     * Menyimpan booking pemesanan baru (Hanya Transaksi Pasien).
     */
    public function store(Request $request)
    {
        // 1. Bersihkan format Rupiah pada biaya_pemasangan jika ada
        if ($request->has('biaya_pemasangan')) {
            $request->merge([
                'biaya_pemasangan' => str_replace(['.', ','], ['', '.'], $request->biaya_pemasangan)
            ]);
        }

        // 2. Validasi input pasien
        $request->validate([
            'no_pemesanan'        => 'required|unique:pemesanan,no_pemesanan',
            'id_pemeriksaan'      => 'required|exists:pemeriksaan,id',
            'biaya_pemasangan'    => 'nullable|numeric|min:0',
            'diskon'              => 'nullable|numeric|min:0|max:100',
            'status_bayar_pasien' => 'nullable|in:belum_lunas,sudah_lunas',
            'items'               => 'required|array|min:1',
            'items.*'             => 'required|exists:jenis_gigi,id',
        ]);

        DB::beginTransaction();
        try {
            // 3. Kalkulasi harga pasien (Total Jenis Gigi + Biaya Pasang - Diskon)
            $jenisGigiList = JenisGigi::whereIn('id', $request->items)->get()->keyBy('id');

            $totalHargaGigi = 0;
            foreach ($request->items as $id_gigi) {
                $totalHargaGigi += (float) ($jenisGigiList[$id_gigi]->estimasi_biaya ?? 0);
            }

            $biayaPemasangan = (float) ($request->biaya_pemasangan ?? 0);
            $diskonPersen    = (float) ($request->diskon ?? 0);

            $subtotal      = $totalHargaGigi + $biayaPemasangan;
            $nominalDiskon = $subtotal * ($diskonPersen / 100);
            $hargaPasien   = max(0, round($subtotal - $nominalDiskon));

            // 4. Simpan pemesanan pasien (status default: 'pending')
            $pemesanan = Pemesanan::create([
                'no_pemesanan'        => $request->no_pemesanan,
                'id_pemeriksaan'      => $request->id_pemeriksaan,
                'harga_pasien'        => $hargaPasien,
                'diskon'              => $diskonPersen,
                'biaya_pemasangan'    => $biayaPemasangan,
                'status_bayar_pasien' => $request->input('status_bayar_pasien', 'belum_lunas'),
                'status_booking'      => 'pending',
            ]);

            // 5. Simpan snapshot harga item ke tabel pivot
            foreach ($request->items as $id_gigi) {
                $gigi = $jenisGigiList->get($id_gigi);
                PemesananItem::create([
                    'id_pemesanan'      => $pemesanan->id,
                    'id_jenis_gigi'     => $id_gigi,
                    'harga_jual_pasien' => (float) ($gigi->estimasi_biaya ?? 0),
                    'biaya_modal_lab'   => null,
                ]);
            }

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Booking pemesanan pasien berhasil dibuat dengan status Pending.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error store pemesanan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withInput()->with('error', 'Gagal membuat booking pemesanan: ' . $e->getMessage());
        }
    }

    /**
     * Memperbarui status alur pemesanan pasien.
     * Menerapkan validasi ketat dosen saat status diubah menjadi 'selesai'.
     */
    public function updateStatus(Request $request, int $id)
    {
        $pemesanan = Pemesanan::with('pesananLab')->findOrFail($id);

        $request->validate([
            'status_booking'      => 'sometimes|in:pending,proses_lab,siap_pasang,selesai',
            'status_bayar_pasien' => 'sometimes|in:belum_lunas,sudah_lunas',
        ]);

        // 🔴 VALIDASI KETAT STATUS 'SELESAI'
        if ($request->input('status_booking') === 'selesai') {
            // Syarat 1: Gigi harus sudah tiba dari laboratorium
            if (!$pemesanan->pesananLab || is_null($pemesanan->pesananLab->tanggal_tiba)) {
                return back()->with('error', 'Gigi belum tiba dari laboratorium.');
            }

            // Syarat 2: Pembayaran pasien harus sudah lunas
            $statusBayar = $request->input('status_bayar_pasien', $pemesanan->status_bayar_pasien);
            if ($statusBayar !== 'sudah_lunas') {
                return back()->with('error', 'Pembayaran pasien belum lunas.');
            }
        }

        DB::beginTransaction();
        try {
            $pemesanan->update(
                $request->only(['status_booking', 'status_bayar_pasien'])
            );

            // Bersihkan notifikasi jika pesanan sudah selesai
            if ($pemesanan->status_booking === 'selesai') {
                Notifikasi::where('pemesanan_id', $id)->delete();
            }

            DB::commit();

            return back()->with('success', 'Status pemesanan berhasil diperbarui!');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error updateStatus pemesanan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Gagal memperbarui status: ' . $e->getMessage());
        }
    }

    /**
     * Form edit rincian booking pasien (Khusus Direktur).
     */
    public function edit(int $id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pemesanan.index')->with('error', 'Akses Ditolak! Hanya Direktur yang berhak mengedit data pemesanan.');
        }

        return view('pemesanan.update', [
            'data'        => Pemesanan::with(['pesananLab', 'items.jenisGigi'])->findOrFail($id),
            'pemeriksaan' => Pemeriksaan::all(),
            'jenis_gigi'  => JenisGigi::where('is_aktif', true)->get()
        ]);
    }

    /**
     * Memperbarui rincian booking pasien (Khusus Direktur).
     */
    public function update(Request $request, int $id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pemesanan.index')->with('error', 'Akses Ditolak!');
        }

        $pemesanan = Pemesanan::with('pesananLab')->findOrFail($id);

        if ($request->has('biaya_pemasangan')) {
            $request->merge([
                'biaya_pemasangan' => str_replace(['.', ','], ['', '.'], $request->biaya_pemasangan)
            ]);
        }

        $request->validate([
            'no_pemesanan'        => 'required|unique:pemesanan,no_pemesanan,' . $id,
            'id_pemeriksaan'      => 'required|exists:pemeriksaan,id',
            'biaya_pemasangan'    => 'nullable|numeric|min:0',
            'diskon'              => 'nullable|numeric|min:0|max:100',
            'status_bayar_pasien' => 'required|in:belum_lunas,sudah_lunas',
            'status_booking'      => 'required|in:pending,proses_lab,siap_pasang,selesai',
            'items'               => 'required|array|min:1',
            'items.*'             => 'required|exists:jenis_gigi,id',
        ]);

        // Validasi ketat jika status diubah ke 'selesai'
        if ($request->status_booking === 'selesai') {
            if (!$pemesanan->pesananLab || is_null($pemesanan->pesananLab->tanggal_tiba)) {
                return back()->withInput()->with('error', 'Gigi belum tiba dari laboratorium.');
            }
            if ($request->status_bayar_pasien !== 'sudah_lunas') {
                return back()->withInput()->with('error', 'Pembayaran pasien belum lunas.');
            }
        }

        DB::beginTransaction();
        try {
            $jenisGigiList = JenisGigi::whereIn('id', $request->items)->get()->keyBy('id');
            $totalHargaGigi = 0;
            foreach ($request->items as $id_gigi) {
                $totalHargaGigi += (float) ($jenisGigiList[$id_gigi]->estimasi_biaya ?? 0);
            }

            $biayaPemasangan = (float) ($request->biaya_pemasangan ?? 0);
            $diskonPersen    = (float) ($request->diskon ?? 0);

            $subtotal      = $totalHargaGigi + $biayaPemasangan;
            $nominalDiskon = $subtotal * ($diskonPersen / 100);
            $hargaPasien   = max(0, round($subtotal - $nominalDiskon));

            $pemesanan->update([
                'no_pemesanan'        => $request->no_pemesanan,
                'id_pemeriksaan'      => $request->id_pemeriksaan,
                'harga_pasien'        => $hargaPasien,
                'biaya_pemasangan'    => $biayaPemasangan,
                'diskon'              => $diskonPersen,
                'status_bayar_pasien' => $request->status_bayar_pasien,
                'status_booking'      => $request->status_booking,
            ]);

            // Sinkronisasi item gigi di pivot
            PemesananItem::where('id_pemesanan', $id)->delete();
            foreach ($request->items as $id_gigi) {
                $gigi = $jenisGigiList->get($id_gigi);
                PemesananItem::create([
                    'id_pemesanan'      => $id,
                    'id_jenis_gigi'     => $id_gigi,
                    'harga_jual_pasien' => (float) ($gigi->estimasi_biaya ?? 0),
                    'biaya_modal_lab'   => null,
                ]);
            }

            if ($pemesanan->status_booking === 'selesai') {
                Notifikasi::where('pemesanan_id', $id)->delete();
            }

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Berhasil memperbarui data booking pemesanan!');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error update pemesanan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withInput()->with('error', 'Gagal memperbarui pemesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menghapus pemesanan (Khusus Direktur).
     */
    public function destroy(int $id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        DB::beginTransaction();
        try {
            $pemesanan = Pemesanan::findOrFail($id);
            $pemesanan->delete();

            DB::commit();

            return redirect()->route('pemesanan.index')
                ->with('success', 'Berhasil menghapus pemesanan!');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error destroy pemesanan: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Gagal menghapus data: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan riwayat pemesanan yang siap pasang atau selesai.
     */
    public function pemesananRiwayat(Request $request)
    {
        $query = Pemesanan::with(['pemeriksaan.pasien', 'pesananLab.lab'])
            ->whereIn('status_booking', ['siap_pasang', 'selesai']);

        if ($request->filled('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('no_pemesanan', 'like', "%{$cari}%")
                    ->orWhereHas('pemeriksaan.pasien', function ($qp) use ($cari) {
                        $qp->where('nama', 'like', "%{$cari}%");
                    })
                    ->orWhereHas('pesananLab.lab', function ($ql) use ($cari) {
                        $ql->where('nama_lab', 'like', "%{$cari}%");
                    });
            });
        }

        $riwayat = $query->latest('updated_at')->paginate(10);

        $totalPesanan   = Pemesanan::count();
        $telahTiba      = Pemesanan::where('status_booking', 'siap_pasang')->count();
        $pesananSelesai = Pemesanan::where('status_booking', 'selesai')->count();

        return view('riwayat_pemesanan.index', compact(
            'riwayat',
            'totalPesanan',
            'telahTiba',
            'pesananSelesai'
        ));
    }
}
