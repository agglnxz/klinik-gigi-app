<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class LaporanPemesananExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithCustomStartCell,
    WithEvents
{
    private ?Collection $data = null;
    private int $nomor = 0;

    public function __construct(
        private readonly ?string $search = null,
        private readonly ?string $dari = null,
        private readonly ?string $sampai = null,
        private readonly ?string $status = null,
    ) {
    }

    public function collection(): Collection
    {
        if ($this->data !== null) {
            return $this->data;
        }

        return $this->data = Pemesanan::with([
            'pemeriksaan.pasien',
            'lab',
            'items.jenisGigi',
        ])
            ->whereIn('status_pemesanan', [
                'dalam_proses',
                'tiba_di_klinik',
                'selesai',
            ])
            ->when($this->dari, function ($query) {
                $query->whereDate('tanggal_dikirim', '>=', $this->dari);
            })
            ->when($this->sampai, function ($query) {
                $query->whereDate('tanggal_dikirim', '<=', $this->sampai);
            })
            ->when($this->status, function ($query) {
                $query->where('status_pemesanan', $this->status);
            })
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($query) use ($search) {
                    $query->whereHas(
                        'pemeriksaan.pasien',
                        fn ($q) => $q->where('nama', 'like', $search)
                    )
                    ->orWhereHas(
                        'lab',
                        fn ($q) => $q->where('nama_lab', 'like', $search)
                    )
                    ->orWhereHas(
                        'items.jenisGigi',
                        fn ($q) => $q->where('nama_jenis', 'like', $search)
                    );
                });
            })
            ->orderByDesc('tanggal_dikirim')
            ->get();
    }

    public function startCell(): string
    {
        return 'A9';
    }

    public function headings(): array
    {
        return [
            'No',
            'No Pemesanan',
            'Tgl Pesan',
            'Nama Pasien',
            'Jenis Gigi',
            'Tanggal Kirim',
            'Estimasi Selesai',
            'Tanggal Lunas Pembayaran',
            'Laboratorium',
            'Biaya Lab',
            'Total Harga',
            'Status',
        ];
    }

    private function formatTanggal($tanggal): string
    {
        if (empty($tanggal)) {
            return '-';
        }

        try {
            return Carbon::parse($tanggal)->format('d/m/Y');
        } catch (\Throwable $e) {
            return '-';
        }
    }

    public function map($pemesanan): array
    {
        $this->nomor++;

        $tanggalLunas =
            $pemesanan->tanggal_lunas_pembayaran
            ?? $pemesanan->tanggal_lunas
            ?? $pemesanan->tanggal_lunas_pasien
            ?? $pemesanan->tanggal_bayar
            ?? null;

        $biayaLab = $pemesanan->getRawOriginal('biaya_lab');
        $hargaPasien = $pemesanan->getRawOriginal('harga_pasien');

        $biayaLab = ($biayaLab === null || $biayaLab === '')
            ? 0
            : (float) $biayaLab;

        $hargaPasien = ($hargaPasien === null || $hargaPasien === '')
            ? 0
            : (float) $hargaPasien;

        return [
            $this->nomor,
            $pemesanan->no_pemesanan ?? '-',
            $this->formatTanggal($pemesanan->created_at),
            $pemesanan->pemeriksaan?->pasien?->nama ?? 'Pasien Terhapus',
            $pemesanan->items
                ->pluck('jenisGigi.nama_jenis')
                ->filter()
                ->unique()
                ->join(', ') ?: '-',
            $this->formatTanggal($pemesanan->tanggal_dikirim),
            $this->formatTanggal($pemesanan->estimasi_selesai),
            $this->formatTanggal($tanggalLunas),
            $pemesanan->lab?->nama_lab ?? '-',
            $biayaLab,
            $hargaPasien,
            match ($pemesanan->status_pemesanan) {
                'dalam_proses' => 'Belum Tiba',
                'tiba_di_klinik' => 'Tiba di Klinik',
                'selesai' => 'Selesai',
                default => $pemesanan->status_pemesanan,
            },
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $data = $this->collection();

                $headerRow = 9;
                $dataStartRow = 10;
                $lastDataRow = $dataStartRow + $data->count() - 1;
                $totalRow = max($dataStartRow, $lastDataRow + 2);

                $blueDark = '174A7E';
                $blue = '1769AA';
                $blueLight = 'EAF4FC';
                $blueSoft = 'F7FBFE';
                $border = 'B8CEDF';
                $white = 'FFFFFF';
                $text = '173B5C';

                // KOP KLINIK
                $sheet->mergeCells('A1:B3');
                $sheet->mergeCells('C1:L1');
                $sheet->mergeCells('C2:L2');
                $sheet->mergeCells('C3:L3');
                $sheet->mergeCells('A4:L4');

                $sheet->setCellValue('C1', 'KLINIK GIGI WINARDI');

                $sheet->setCellValue(
                    'C2',
                    'Alamat: Jl. KH Hasyim Asyari No. 88, Pancoran Kulon, Rogojampi, Banyuwangi, Jawa Timur 68462'
                );

                $sheet->setCellValue(
                    'C3',
                    'Telepon: 082131797341'
                );

                // LOGO TANPA GARIS PEMBATAS
                $lokasiLogo = [
                    public_path('images/logo-winardi.png'),
                    public_path('logo-winardi.png'),
                ];

                foreach ($lokasiLogo as $pathLogo) {
                    if (file_exists($pathLogo)) {
                        $drawing = new Drawing();
                        $drawing->setName('Logo Klinik');
                        $drawing->setDescription('Logo Klinik Gigi Winardi');
                        $drawing->setPath($pathLogo);
                        $drawing->setCoordinates('A1');
                        $drawing->setHeight(64);
                        $drawing->setOffsetX(12);
                        $drawing->setOffsetY(1);
                        $drawing->setWorksheet($sheet);
                        break;
                    }
                }

                // Hilangkan seluruh border di area logo.
                $sheet->getStyle('A1:B3')
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_NONE);

                $sheet->getStyle('A1:B3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                // NAMA KLINIK
                $sheet->getStyle('C1:L1')->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 15,
                        'bold' => true,
                        'color' => ['rgb' => $blueDark],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_BOTTOM,
                    ],
                ]);

                // ALAMAT DAN TELEPON DIBUAT RAPAT
                $sheet->getStyle('C2:L3')->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 9,
                        'color' => ['rgb' => $text],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => false,
                    ],
                ]);

                // JARAK ANTARBARIS KOP DIPERKECIL
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(18);
                $sheet->getRowDimension(3)->setRowHeight(18);
                $sheet->getRowDimension(4)->setRowHeight(8);

                // GARIS PEMISAH KOP
                $sheet->getStyle('A4:L4')
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB($blueDark);

                // JUDUL LAPORAN
                $judul = match ($this->status) {
                    'dalam_proses' => 'LAPORAN PEMESANAN GIGI PALSU - BELUM TIBA',
                    'tiba_di_klinik' => 'LAPORAN PEMESANAN GIGI PALSU - TIBA DI KLINIK',
                    'selesai' => 'LAPORAN PEMESANAN GIGI PALSU - SELESAI',
                    default => 'LAPORAN PEMESANAN GIGI PALSU',
                };

                $sheet->mergeCells('A5:L5');
                $sheet->setCellValue('A5', $judul);

                $sheet->getStyle('A5:L5')->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => $blueDark],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getRowDimension(5)->setRowHeight(27);

                // INFORMASI LAPORAN
                $periodeDari = $this->dari
                    ? date('d/m/Y', strtotime($this->dari))
                    : 'Semua tanggal';

                $periodeSampai = $this->sampai
                    ? date('d/m/Y', strtotime($this->sampai))
                    : 'Semua tanggal';

                $statusText = match ($this->status) {
                    'dalam_proses' => 'Belum Tiba',
                    'tiba_di_klinik' => 'Tiba di Klinik',
                    'selesai' => 'Selesai',
                    default => 'Semua Status',
                };

                $sheet->setCellValue('A7', 'Jenis Laporan');
                $sheet->setCellValue('B7', 'Pemesanan Gigi Palsu');
                $sheet->setCellValue('A8', 'Status');
                $sheet->setCellValue('B8', $statusText);
                $sheet->setCellValue('D7', 'Periode Laporan');
                $sheet->setCellValue(
                    'E7',
                    "{$periodeDari} s.d. {$periodeSampai}"
                );
                $sheet->setCellValue('D8', 'Tanggal Cetak');
                $sheet->setCellValue('E8', date('d/m/Y'));

                $sheet->getStyle('A7:E8')->applyFromArray([
                    'font' => [
                        'name' => 'Calibri',
                        'size' => 9,
                        'color' => ['rgb' => $text],
                    ],
                    'alignment' => [
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A7:A8')->getFont()->setBold(true);
                $sheet->getStyle('D7:D8')->getFont()->setBold(true);

                $sheet->getRowDimension(6)->setRowHeight(5);
                $sheet->getRowDimension(7)->setRowHeight(18);
                $sheet->getRowDimension(8)->setRowHeight(18);

                // HEADER TABEL
                $sheet->getStyle("A{$headerRow}:L{$headerRow}")
                    ->applyFromArray([
                        'font' => [
                            'name' => 'Calibri',
                            'bold' => true,
                            'size' => 9,
                            'color' => ['rgb' => $white],
                        ],
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $blue],
                        ],
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                            'wrapText' => true,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_THIN,
                                'color' => ['rgb' => $blueDark],
                            ],
                        ],
                    ]);

                $sheet->getRowDimension($headerRow)->setRowHeight(34);

                // STYLE DATA
                if ($data->count() > 0) {
                    $sheet->getStyle("A{$dataStartRow}:L{$lastDataRow}")
                        ->applyFromArray([
                            'font' => [
                                'name' => 'Calibri',
                                'size' => 9,
                                'color' => ['rgb' => $text],
                            ],
                            'alignment' => [
                                'vertical' => Alignment::VERTICAL_CENTER,
                            ],
                            'borders' => [
                                'allBorders' => [
                                    'borderStyle' => Border::BORDER_THIN,
                                    'color' => ['rgb' => $border],
                                ],
                            ],
                        ]);

                    for ($row = $dataStartRow; $row <= $lastDataRow; $row++) {
                        if (($row - $dataStartRow) % 2 === 1) {
                            $sheet->getStyle("A{$row}:L{$row}")
                                ->getFill()
                                ->setFillType(Fill::FILL_SOLID);

                            $sheet->getStyle("A{$row}:L{$row}")
                                ->getFill()
                                ->getStartColor()
                                ->setRGB($blueSoft);
                        }

                        $sheet->getRowDimension($row)->setRowHeight(21);
                    }

                    // FORMAT RUPIAH
                    $sheet->getStyle("J{$dataStartRow}:K{$lastDataRow}")
                        ->getNumberFormat()
                        ->setFormatCode('"Rp" #,##0;-"Rp" #,##0;"Rp" 0');

                    $sheet->getStyle("J{$dataStartRow}:K{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                    $sheet->getStyle("A{$dataStartRow}:C{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle("F{$dataStartRow}:H{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);

                    $sheet->getStyle("L{$dataStartRow}:L{$lastDataRow}")
                        ->getAlignment()
                        ->setHorizontal(Alignment::HORIZONTAL_CENTER);
                }

                // TOTAL KESELURUHAN
                $totalHarga = $data->sum(
                    fn ($item) => (float) (
                        $item->getRawOriginal('harga_pasien') ?? 0
                    )
                );

                $sheet->mergeCells("A{$totalRow}:J{$totalRow}");
                $sheet->setCellValue("A{$totalRow}", 'TOTAL KESELURUHAN');
                $sheet->setCellValue("K{$totalRow}", $totalHarga);

                $sheet->getStyle("A{$totalRow}:L{$totalRow}")
                    ->applyFromArray([
                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => ['rgb' => $blueLight],
                        ],
                        'font' => [
                            'name' => 'Calibri',
                            'bold' => true,
                            'size' => 10,
                            'color' => ['rgb' => $blueDark],
                        ],
                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => Border::BORDER_NONE,
                            ],
                        ],
                    ]);

                $sheet->getStyle("A{$totalRow}:L{$totalRow}")
                    ->getBorders()
                    ->getTop()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB($blueDark);

                $sheet->getStyle("A{$totalRow}:L{$totalRow}")
                    ->getBorders()
                    ->getBottom()
                    ->setBorderStyle(Border::BORDER_MEDIUM)
                    ->getColor()
                    ->setRGB($blueDark);

                $sheet->getStyle("K{$totalRow}")
                    ->getNumberFormat()
                    ->setFormatCode('"Rp" #,##0;-"Rp" #,##0;"Rp" 0');

                $sheet->getStyle("K{$totalRow}")
                    ->getAlignment()
                    ->setHorizontal(Alignment::HORIZONTAL_RIGHT);

                $sheet->getRowDimension($totalRow)->setRowHeight(25);

                // LEBAR KOLOM DISESUAIKAN AGAR CETAKAN LEBIH RINGKAS
                $widths = [
                    'A' => 6,
                    'B' => 17,
                    'C' => 12,
                    'D' => 21,
                    'E' => 22,
                    'F' => 13,
                    'G' => 15,
                    'H' => 19,
                    'I' => 18,
                    'J' => 13,
                    'K' => 15,
                    'L' => 14,
                ];

                foreach ($widths as $column => $width) {
                    $sheet->getColumnDimension($column)->setWidth($width);
                }

                $sheet->freezePane('A10');

                // PENGATURAN CETAK
                $sheet->getPageSetup()
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4)
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageSetup()->setFitToPage(true);

                // Area cetak sesuai isi laporan.
                $sheet->getPageSetup()->setPrintArea("A1:L{$totalRow}");

                // Margin cetak lebih seimbang.
                $sheet->getPageMargins()
                    ->setTop(0.3)
                    ->setRight(0.3)
                    ->setLeft(0.3)
                    ->setBottom(0.4)
                    ->setHeader(0.1)
                    ->setFooter(0.1);

                $sheet->getStyle("A1:L{$totalRow}")
                    ->getFont()
                    ->setName('Calibri');

                $sheet->getProtection()->setSheet(false);
            },
        ];
    }
}
