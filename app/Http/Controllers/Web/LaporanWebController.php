<?php

namespace App\Http\Controllers\Web;

use App\Exports\LaporanPemesananExport;
use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class LaporanWebController extends Controller
{
    public function export(Request $request)
    {
        return Excel::download(
            new LaporanPemesananExport(
                $request->string('search')->trim()->value() ?: null,
                $request->string('dari')->value() ?: null,
                $request->string('sampai')->value() ?: null,
                $request->string('status')->value() ?: null,
            ),
            'laporan_pemesanan_gigi_palsu.xlsx'
        );
    }

    public function index()
    {
        $pesanan = Pemesanan::with(['pemeriksaan.pasien', 'lab', 'items.jenisGigi'])
            ->whereIn('status_pemesanan', ['dalam_proses', 'tiba_di_klinik', 'selesai'])
            ->orderByDesc('tanggal_dikirim')
            ->get();

        $reportRows = $pesanan->map(function ($item) {
            return [
                'id' => $item->id,
                'no' => $item->no_pemesanan,
                'pasien' => $item->pemeriksaan->pasien->nama ?? 'Pasien Terhapus',
                'jenis' => $item->items->pluck('jenisGigi.nama_jenis')->filter()->join(', ') ?: '-',
                'tgl' => optional($item->tanggal_dikirim)->format('Y-m-d'),
                'tglSelesai' => optional($item->estimasi_selesai)->format('Y-m-d'),
                'laboratorium' => $item->lab->nama_lab ?? '-',
                'biayaLab' => (float) $item->biaya_lab,
                'bayarLab' => $item->status_bayar_lab === 'sudah_lunas' ? (float) $item->biaya_lab : 0,
                'harga' => (float) $item->harga_pasien,
                'status' => $item->status_pemesanan,
            ];
        })->values();

        return view('laporan.index', [
            'pesanan' => $pesanan,
            'reportRows' => $reportRows,
            'pesananBelumTiba' => $pesanan->where('status_pemesanan', 'dalam_proses')->count(),
            'pesananSudahTiba' => $pesanan->whereIn('status_pemesanan', ['tiba_di_klinik', 'selesai'])->count(),
        ]);
    }
}
