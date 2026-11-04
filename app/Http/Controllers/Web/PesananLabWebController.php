<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\PesananLab;
use App\Models\Pemesanan;
use App\Models\Laboratorium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Throwable;

class PesananLabWebController extends Controller
{
    /**
     * Menampilkan daftar pemesanan ke laboratorium rekanan (Operasional Vendor).
     */
    public function index(Request $request)
    {
        $query = PesananLab::with(['pemesanan.pemeriksaan.pasien', 'lab']);

        // 1. Filter Pencarian (No Pemesanan, Nama Pasien, Nama Lab)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('pemesanan', function ($qp) use ($search) {
                    $qp->where('no_pemesanan', 'like', "%{$search}%")
                       ->orWhereHas('pemeriksaan.pasien', function ($qpas) use ($search) {
                           $qpas->where('nama', 'like', "%{$search}%");
                       });
                })->orWhereHas('lab', function ($ql) use ($search) {
                    $ql->where('nama_lab', 'like', "%{$search}%");
                });
            });
        }

        // 2. Filter Status Pembayaran Lab
        if ($request->filled('status_bayar_lab')) {
            $query->where('status_bayar_lab', $request->status_bayar_lab);
        }

        // 3. Filter Vendor Laboratorium
        if ($request->filled('id_lab')) {
            $query->where('id_lab', $request->id_lab);
        }

        // 4. Filter Status Pengerjaan (sedang_diproses / sudah_tiba)
        if ($request->filled('status_pengerjaan')) {
            if ($request->status_pengerjaan === 'sudah_tiba') {
                $query->whereNotNull('tanggal_tiba');
            } elseif ($request->status_pengerjaan === 'sedang_diproses') {
                $query->whereNull('tanggal_tiba');
            }
        }

        $data = $query->latest('tanggal_dikirim')->paginate(10)->withQueryString();

        // Statistik Operasional Laboratorium
        $totalPesananLab = PesananLab::count();
        $sedangDiproses  = PesananLab::whereNull('tanggal_tiba')->count();
        $sudahTiba       = PesananLab::whereNotNull('tanggal_tiba')->count();
        $jatuhTempo      = PesananLab::hampirJatuhTempo()->count();

        $laboratoriumList = Laboratorium::where('is_aktif', true)->get();

        return view('pesanan_lab.index', compact(
            'data',
            'totalPesananLab',
            'sedangDiproses',
            'sudahTiba',
            'jatuhTempo',
            'laboratoriumList'
        ));
    }

    /**
     * Menampilkan form untuk membuat pesanan ke laboratorium.
     * Mendukung pemanggilan langsung (/pesanan-lab/create) atau dari tombol spesifik (/pesanan-lab/create/{id_pemesanan}).
     */
    public function create(?int $id_pemesanan = null)
    {
        // Ambil semua pemesanan pasien yang status booking-nya masih 'pending' (belum dikirim ke lab)
        $pemesanan_pending = Pemesanan::where('status_booking', 'pending')
            ->with(['pemeriksaan.pasien', 'items.jenisGigi'])
            ->latest('id')
            ->get();

        $selected_id = $id_pemesanan;
        $laboratorium = Laboratorium::where('is_aktif', true)->get();

        return view('pesanan_lab.create', compact('pemesanan_pending', 'selected_id', 'laboratorium'));
    }

    /**
     * Menyimpan data order baru ke laboratorium rekanan.
     * Mengubah status_booking pemesanan menjadi 'proses_lab'.
     */
    public function store(Request $request, ?int $id_pemesanan = null)
    {
        $idPemesanan = $id_pemesanan ?? $request->input('id_pemesanan');

        if (!$idPemesanan) {
            return back()->withInput()->with('error', 'Silakan pilih pemesanan pasien yang hendak dikirim ke lab.');
        }

        $pemesanan = Pemesanan::findOrFail($idPemesanan);

        // 1. Bersihkan format ribuan pada biaya_lab
        if ($request->has('biaya_lab')) {
            $request->merge([
                'biaya_lab' => str_replace(['.', ','], ['', '.'], $request->biaya_lab)
            ]);
        }

        // 2. Validasi input operasional lab
        $request->validate([
            'id_pemesanan'     => 'sometimes|exists:pemesanan,id',
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date|after_or_equal:tanggal_dikirim',
            'biaya_lab'        => 'required|numeric|min:0',
            'status_bayar_lab' => 'nullable|in:belum_lunas,sudah_lunas',
        ]);

        DB::beginTransaction();
        try {
            // 3. Simpan order laboratorium
            $pesananLab = PesananLab::create([
                'id_pemesanan'     => $pemesanan->id,
                'id_lab'           => $request->id_lab,
                'tanggal_dikirim'  => $request->tanggal_dikirim,
                'estimasi_selesai' => $request->estimasi_selesai,
                'biaya_lab'        => $request->biaya_lab,
                'status_bayar_lab' => $request->input('status_bayar_lab', 'belum_lunas'),
                'tanggal_tiba'     => null,
            ]);

            // 4. Update status_booking di tabel pemesanan menjadi 'proses_lab'
            $pemesanan->update([
                'status_booking' => 'proses_lab',
            ]);

            DB::commit();

            return redirect()->route('pesanan-lab.index')
                ->with('success', 'Order pekerjaan laboratorium berhasil dibuat. Status booking pasien: Proses Lab.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error store PesananLab: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withInput()->with('error', 'Gagal memproses pesanan laboratorium: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan detail Surat Perintah Kerja (SPK) Laboratorium.
     */
    public function show(int $id)
    {
        $data = PesananLab::with(['pemesanan.pemeriksaan.pasien', 'pemesanan.items.jenisGigi', 'lab'])
            ->findOrFail($id);

        return view('pesanan_lab.show', compact('data'));
    }

    /**
     * Form edit rincian order laboratorium (Khusus Direktur).
     */
    public function edit(int $id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pesanan-lab.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak mengedit data pesanan laboratorium.');
        }

        $data = PesananLab::with(['pemesanan.pemeriksaan.pasien', 'lab'])->findOrFail($id);
        $laboratorium = Laboratorium::where('is_aktif', true)->get();

        return view('pesanan_lab.edit', compact('data', 'laboratorium'));
    }

    /**
     * Memperbarui data order laboratorium (Khusus Direktur).
     */
    public function update(Request $request, int $id)
    {
        if (Auth::check() && strtolower(Auth::user()->role) !== 'direktur') {
            return redirect()->route('pesanan-lab.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak mengedit data pesanan laboratorium.');
        }

        $pesananLab = PesananLab::with('pemesanan')->findOrFail($id);

        if ($request->has('biaya_lab')) {
            $request->merge([
                'biaya_lab' => str_replace(['.', ','], ['', '.'], $request->biaya_lab)
            ]);
        }

        $request->validate([
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date|after_or_equal:tanggal_dikirim',
            'tanggal_tiba'     => 'nullable|date',
            'biaya_lab'        => 'required|numeric|min:0',
            'status_bayar_lab' => 'required|in:belum_lunas,sudah_lunas',
        ]);

        DB::beginTransaction();
        try {
            $pesananLab->update([
                'id_lab'           => $request->id_lab,
                'tanggal_dikirim'  => $request->tanggal_dikirim,
                'estimasi_selesai' => $request->estimasi_selesai,
                'tanggal_tiba'     => $request->tanggal_tiba,
                'biaya_lab'        => $request->biaya_lab,
                'status_bayar_lab' => $request->status_bayar_lab,
            ]);

            // Jika tanggal_tiba diisi dan status booking masih 'proses_lab', ubah jadi 'siap_pasang'
            if ($request->filled('tanggal_tiba') && $pesananLab->pemesanan->status_booking === 'proses_lab') {
                $pesananLab->pemesanan->update(['status_booking' => 'siap_pasang']);
            }

            DB::commit();

            return redirect()->route('pesanan-lab.index')
                ->with('success', 'Data pesanan laboratorium berhasil diperbarui!');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error update PesananLab: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->withInput()->with('error', 'Gagal memperbarui pesanan laboratorium: ' . $e->getMessage());
        }
    }

    /**
     * Aksi Khusus: Tandai Telah Tiba dari Laboratorium (markAsArrived).
     * Mengisi tanggal_tiba dengan tanggal hari ini dan otomatis mengubah status_booking di tabel pemesanan induk menjadi 'siap_pasang'.
     */
    public function markAsArrived(Request $request, int $id)
    {
        $pesananLab = PesananLab::with('pemesanan')->findOrFail($id);

        $tanggalTiba = $request->input('tanggal_tiba', Carbon::today()->toDateString());

        DB::beginTransaction();
        try {
            $pesananLab->update([
                'tanggal_tiba' => $tanggalTiba,
            ]);

            // Otomatis ubah status booking di tabel pemesanan induk menjadi 'siap_pasang'
            if ($pesananLab->pemesanan) {
                $pesananLab->pemesanan->update([
                    'status_booking' => 'siap_pasang',
                ]);
            }

            DB::commit();

            return back()->with('success', 'Gigi berhasil ditandai telah tiba di klinik. Status pemesanan induk kini: Siap Pasang.');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error markAsArrived PesananLab: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Gagal menandai kedatangan barang: ' . $e->getMessage());
        }
    }

    /**
     * Alias konfirmasiTiba untuk kompatibilitas rute.
     */
    public function konfirmasiTiba(Request $request, int $id)
    {
        return $this->markAsArrived($request, $id);
    }

    /**
     * Aksi Cepat: Memperbarui status bayar ke vendor laboratorium (belum_lunas <-> sudah_lunas).
     */
    public function updateStatusBayar(Request $request, int $id)
    {
        $pesananLab = PesananLab::findOrFail($id);

        $request->validate([
            'status_bayar_lab' => 'required|in:belum_lunas,sudah_lunas',
        ]);

        DB::beginTransaction();
        try {
            $pesananLab->update([
                'status_bayar_lab' => $request->status_bayar_lab,
            ]);

            DB::commit();

            return back()->with('success', 'Status pembayaran laboratorium berhasil diperbarui!');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error updateStatusBayar PesananLab: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            return back()->with('error', 'Gagal memperbarui status bayar lab: ' . $e->getMessage());
        }
    }
}

