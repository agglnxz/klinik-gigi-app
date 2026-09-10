<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanPemesananExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly ?string $search = null,
        private readonly ?string $dari = null,
        private readonly ?string $sampai = null,
        private readonly ?string $status = null,
    ) {
    }

    public function collection(): Collection
    {
        return Pemesanan::with(['pemeriksaan.pasien', 'lab', 'items.jenisGigi'])
            ->whereIn('status_pemesanan', ['dalam_proses', 'tiba_di_klinik', 'selesai'])
            ->when($this->dari, fn ($query) => $query->whereDate('tanggal_dikirim', '>=', $this->dari))
            ->when($this->sampai, fn ($query) => $query->whereDate('tanggal_dikirim', '<=', $this->sampai))
            ->when($this->status, fn ($query) => $query->where('status_pemesanan', $this->status))
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($query) use ($search) {
                    $query->whereHas('pemeriksaan.pasien', fn ($query) => $query->where('nama', 'like', $search))
                        ->orWhereHas('lab', fn ($query) => $query->where('nama_lab', 'like', $search))
                        ->orWhereHas('items.jenisGigi', fn ($query) => $query->where('nama_jenis', 'like', $search));
                });
            })
            ->orderByDesc('tanggal_dikirim')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal Pesan',
            'Nama Pasien',
            'Jenis Gigi Palsu',
            'Tanggal Kirim',
            'Estimasi Selesai',
            'Laboratorium',
            'Biaya Lab (Rp)',
            'Bayar Lab (Rp)',
            'Harga Pasien (Rp)',
            'Status',
        ];
    }

    public function map($pemesanan): array
    {
        return [
            $pemesanan->no_pemesanan,
            optional($pemesanan->created_at)->format('Y-m-d'),
            $pemesanan->pemeriksaan->pasien->nama ?? 'Pasien Terhapus',
            $pemesanan->items->pluck('jenisGigi.nama_jenis')->filter()->join(', ') ?: '-',
            optional($pemesanan->tanggal_dikirim)->format('Y-m-d'),
            optional($pemesanan->estimasi_selesai)->format('Y-m-d'),
            $pemesanan->lab->nama_lab ?? '-',
            (float) $pemesanan->biaya_lab,
            $pemesanan->status_bayar_lab === 'sudah_lunas' ? (float) $pemesanan->biaya_lab : 0,
            (float) $pemesanan->harga_pasien,
            match ($pemesanan->status_pemesanan) {
                'dalam_proses' => 'Belum Tiba',
                'tiba_di_klinik' => 'Tiba di Klinik',
                'selesai' => 'Selesai',
                default => $pemesanan->status_pemesanan,
            },
        ];
    }
}
