<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\Pemeriksaan;
use App\Models\Laboratorium;
use App\Models\JenisGigi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PemesananWebController extends Controller
{
    public function index()
    {
        // Load relasi items dan jenisGigi
        $data = Pemesanan::with(['pemeriksaan.pasien', 'lab', 'items.jenisGigi'])->get();

        // 2. Hitung statistik dinamis
        $totalPesanan    = Pemesanan::count();
        $sedangDiproses  = Pemesanan::where('status_pemesanan', 'diproses')->count();
        $pesananSelesai  = Pemesanan::where('status_pemesanan', 'selesai')->count();
        return view('pemesanan.index', compact('data', 'totalPesanan', 'sedangDiproses', 'pesananSelesai'));
    }

    public function create()
    {
        // 1. Logika Penomoran Otomatis: PSN-YYYYMMDD-NNN
        $datePrefix = now()->format('Ymd');
        $lastRecord = Pemesanan::where('no_pemesanan', 'like', 'PSN-' . $datePrefix . '-%')
            ->latest('id')
            ->first();

        $nextUrutan = $lastRecord ? ((int) substr($lastRecord->no_pemesanan, -3) + 1) : 1;
        $no_pemesanan = 'PSN-' . $datePrefix . '-' . str_pad($nextUrutan, 3, '0', STR_PAD_LEFT);

        // 2. Ambil data master untuk isian form
        $pemeriksaan = Pemeriksaan::with('pasien')->get();
        $lab         = Laboratorium::where('is_aktif', true)->get();
        $jenis_gigi  = JenisGigi::where('is_aktif', true)->get();

        return view('pemesanan.create', compact('no_pemesanan', 'pemeriksaan', 'lab', 'jenis_gigi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan,no_pemesanan',
            'id_pemeriksaan'   => 'required|exists:pemeriksaan,id',
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date',
            'biaya_lab'        => 'required|numeric',
            'harga_pasien'     => 'required|numeric',
            'status_bayar_lab' => 'required|string',
            'status_pemesanan' => 'required|string',
            'items'            => 'required|array|min:1',
            'items.*'          => 'required|exists:jenis_gigi,id',
        ]);

        // 1. Simpan induk pemesanan (tanpa array items)
        $pemesanan = Pemesanan::create($request->except('items'));

        // 2. Simpan banyak gigi ke tabel perantara
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

        $request->validate([
            'no_pemesanan'     => 'required|unique:pemesanan,no_pemesanan,' . $id,
            'id_pemeriksaan'   => 'required|exists:pemeriksaan,id',
            'id_lab'           => 'required|exists:laboratorium,id',
            'tanggal_dikirim'  => 'required|date',
            'estimasi_selesai' => 'required|date',
            'biaya_lab'        => 'required|numeric',
            'harga_pasien'     => 'required|numeric',
            'status_bayar_lab' => 'required|string',
            'status_pemesanan' => 'required|string',
            'items'            => 'required|array|min:1',
            'items.*'          => 'required|exists:jenis_gigi,id',
        ]);

        // 1. Update induk
        $pemesanan->update($request->except('items'));

        // 2. Hapus relasi gigi lama, ganti dengan daftar gigi baru yang dikirim
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
        if (Auth::user()->role !== 'direktur') {
            return redirect()->route('pemesanan.index')
                ->with('error', 'Akses Ditolak! Hanya Direktur yang berhak menghapus data.');
        }

        // Tabel pivot otomatis terhapus karena efek onDelete('cascade') di migration
        Pemesanan::findOrFail($id)->delete();

        return redirect()->route('pemesanan.index')
            ->with('success', 'Berhasil menghapus pesanan!');
    }
}
