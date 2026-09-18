<?php

namespace App\Exports;

use App\Models\Pemesanan;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class LaporanPemesananExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    WithCustomStartCell,
    WithEvents
{
    private ?Collection $data = null;

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
            'items.jenisGigi'
        ])
            ->whereIn('status_pemesanan', [
                'dalam_proses',
                'tiba_di_klinik',
                'selesai'
            ])
            ->when(
                $this->dari,
                fn ($query) =>
                $query->whereDate('tanggal_dikirim', '>=', $this->dari)
            )
            ->when(
                $this->sampai,
                fn ($query) =>
                $query->whereDate('tanggal_dikirim', '<=', $this->sampai)
            )
            ->when(
                $this->status,
                fn ($query) =>
                $query->where('status_pemesanan', $this->status)
            )
            ->when($this->search, function ($query) {
                $search = '%' . $this->search . '%';

                $query->where(function ($query) use ($search) {

                    $query->whereHas(
                        'pemeriksaan.pasien',
                        fn ($query) =>
                        $query->where('nama', 'like', $search)
                    )

                    ->orWhereHas(
                        'lab',
                        fn ($query) =>
                        $query->where('nama_lab', 'like', $search)
                    )

                    ->orWhereHas(
                        'items.jenisGigi',
                        fn ($query) =>
                        $query->where('nama_jenis', 'like', $search)
                    );
                });
            })
            ->orderByDesc('tanggal_dikirim')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | TABEL DIMULAI DARI BARIS 9
    |--------------------------------------------------------------------------
    |
    | Baris 8 sengaja dikosongkan sebagai jarak antara card dan tabel.
    |
    */

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
            'Tgl Kirim',
            'Estimasi Selesai',
            'Laboratorium',
            'Biaya Lab',
            'Bayar Lab',
            'Harga Pasien',
            'Status',
        ];
    }

    public function map($pemesanan): array
    {
        static $no = 0;

        $no++;

        return [
            $no,

            $pemesanan->no_pemesanan,

            optional($pemesanan->created_at)
                ->format('d/m/Y'),

            $pemesanan->pemeriksaan->pasien->nama
                ?? 'Pasien Terhapus',

            $pemesanan->items
                ->pluck('jenisGigi.nama_jenis')
                ->filter()
                ->join(', ') ?: '-',

            optional($pemesanan->tanggal_dikirim)
                ->format('d/m/Y'),

            optional($pemesanan->estimasi_selesai)
                ->format('d/m/Y'),

            $pemesanan->lab->nama_lab ?? '-',

            (float) $pemesanan->biaya_lab,

            $pemesanan->status_bayar_lab === 'sudah_lunas'
                ? (float) $pemesanan->biaya_lab
                : 0,

            (float) $pemesanan->harga_pasien,

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

                /*
                |--------------------------------------------------------------------------
                | POSISI BARIS
                |--------------------------------------------------------------------------
                */

                $headerRow = 9;
                $dataStartRow = 10;

                $lastRow = max(
                    $dataStartRow,
                    $sheet->getHighestRow()
                );

                /*
                |--------------------------------------------------------------------------
                | WARNA
                |--------------------------------------------------------------------------
                */

                $blueDark  = '174A7E';
                $blue      = '1769AA';
                $blueLight = 'EAF4FC';
                $blueSoft  = 'F7FBFE';
                $border    = 'B8CEDF';
                $white     = 'FFFFFF';
                $text      = '173B5C';

                /*
                |--------------------------------------------------------------------------
                | JUDUL UTAMA
                |--------------------------------------------------------------------------
                */

                $sheet->mergeCells('A1:L3');

                $sheet->setCellValue(
                    'A1',
                    'LAPORAN PEMESANAN GIGI PALSU KLINIK GIGI WINARDI'
                );

                $sheet->getStyle('A1:L3')->applyFromArray([

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => $blueLight,
                        ],
                    ],

                    'borders' => [
                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => [
                                'rgb' => $blue,
                            ],
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | POSISI JUDUL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle('A1')->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 20,
                        'color' => [
                            'rgb' => $blueDark,
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_LEFT,
                        'vertical' => Alignment::VERTICAL_TOP,
                        'wrapText' => false,
                    ],
                ]);

                $sheet->getRowDimension(1)->setRowHeight(30);
                $sheet->getRowDimension(2)->setRowHeight(12);
                $sheet->getRowDimension(3)->setRowHeight(10);

                /*
                |--------------------------------------------------------------------------
                | JARAK SEBELUM CARD
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(4)->setRowHeight(20);

                /*
                |--------------------------------------------------------------------------
                | RINGKASAN
                |--------------------------------------------------------------------------
                */

                $data = $this->collection();

                $total = $data->count();

                $belumTiba = $data->where(
                    'status_pemesanan',
                    'dalam_proses'
                )->count();

                $tibaDiKlinik = $data->where(
                    'status_pemesanan',
                    'tiba_di_klinik'
                )->count();

                $selesai = $data->where(
                    'status_pemesanan',
                    'selesai'
                )->count();

                /*
                |--------------------------------------------------------------------------
                | CARD
                |--------------------------------------------------------------------------
                */

                $cards = [
                    ['A5:C5', 'A6:C7', 'TOTAL PEMESANAN', $total],
                    ['D5:F5', 'D6:F7', 'BELUM TIBA', $belumTiba],
                    ['G5:I5', 'G6:I7', 'TIBA DI KLINIK', $tibaDiKlinik],
                    ['J5:L5', 'J6:L7', 'SELESAI', $selesai],
                ];

                foreach ($cards as [$titleRange, $valueRange, $title, $value]) {

                    $sheet->mergeCells($titleRange);
                    $sheet->mergeCells($valueRange);

                    $titleCell = explode(':', $titleRange)[0];
                    $valueCell = explode(':', $valueRange)[0];

                    $sheet->setCellValue($titleCell, $title);
                    $sheet->setCellValue($valueCell, $value);

                    /*
                    |--------------------------------------------------------------------------
                    | JUDUL CARD
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle($titleRange)->applyFromArray([

                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => $blue,
                            ],
                        ],

                        'font' => [
                            'bold' => true,
                            'size' => 10,
                            'color' => [
                                'rgb' => $white,
                            ],
                        ],

                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'left' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'right' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | ISI CARD
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle($valueRange)->applyFromArray([

                        'fill' => [
                            'fillType' => Fill::FILL_SOLID,
                            'startColor' => [
                                'rgb' => $white,
                            ],
                        ],

                        'font' => [
                            'bold' => true,
                            'size' => 20,
                            'color' => [
                                'rgb' => $blueDark,
                            ],
                        ],

                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [
                            'top' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'left' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'right' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],
                        ],
                    ]);
                }

                $sheet->getRowDimension(5)->setRowHeight(22);
                $sheet->getRowDimension(6)->setRowHeight(25);
                $sheet->getRowDimension(7)->setRowHeight(25);

                /*
                |--------------------------------------------------------------------------
                | BARIS 8 = JARAK KOSONG
                |--------------------------------------------------------------------------
                */

                $sheet->getRowDimension(8)->setRowHeight(18);

                /*
                |--------------------------------------------------------------------------
                | HEADER TABEL
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle(
                    "A{$headerRow}:L{$headerRow}"
                )->applyFromArray([

                    'font' => [
                        'bold' => true,
                        'size' => 10,
                        'color' => [
                            'rgb' => $white,
                        ],
                    ],

                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => [
                            'rgb' => $blue,
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],

                    /*
                    |--------------------------------------------------------------------------
                    | SEMUA GARIS HEADER TEBAL
                    |--------------------------------------------------------------------------
                    */

                    'borders' => [
                        'top' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => [
                                'rgb' => $blueDark,
                            ],
                        ],

                        'bottom' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => [
                                'rgb' => $blueDark,
                            ],
                        ],

                        'left' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => [
                                'rgb' => $blueDark,
                            ],
                        ],

                        'right' => [
                            'borderStyle' => Border::BORDER_MEDIUM,
                            'color' => [
                                'rgb' => $blueDark,
                            ],
                        ],
                    ],
                ]);

                $sheet->getRowDimension($headerRow)->setRowHeight(40);

                /*
                |--------------------------------------------------------------------------
                | ISI TABEL
                |--------------------------------------------------------------------------
                */

                if ($lastRow >= $dataStartRow) {

                    /*
                    |--------------------------------------------------------------------------
                    | STYLE DASAR ISI TABEL
                    |--------------------------------------------------------------------------
                    |
                    | SEMUA GARIS ATAS, BAWAH, KIRI, KANAN = MEDIUM
                    |
                    */

                    $sheet->getStyle(
                        "A{$dataStartRow}:L{$lastRow}"
                    )->applyFromArray([

                        'font' => [
                            'name' => 'Calibri',
                            'size' => 10,
                            'color' => [
                                'rgb' => $text,
                            ],
                        ],

                        'alignment' => [
                            'vertical' => Alignment::VERTICAL_CENTER,
                        ],

                        'borders' => [

                            'top' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'bottom' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'left' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],

                            'right' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | PEMBATAS VERTIKAL SETIAP KOLOM
                    |--------------------------------------------------------------------------
                    |
                    | Semua garis:
                    |
                    | A | B | C | D | E | F | G | H | I | J | K | L
                    |
                    | dibuat TEBAL dan SAMA.
                    |
                    */

                    $columns = [
                        'A',
                        'B',
                        'C',
                        'D',
                        'E',
                        'F',
                        'G',
                        'H',
                        'I',
                        'J',
                        'K',
                        'L',
                    ];

                    foreach ($columns as $column) {

                        $sheet->getStyle(
                            "{$column}{$headerRow}:{$column}{$lastRow}"
                        )->applyFromArray([
                            'borders' => [

                                'left' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],

                                'right' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],
                            ],
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | GARIS HORIZONTAL SETIAP BARIS
                    |--------------------------------------------------------------------------
                    |
                    | INI BAGIAN YANG KAMU MAKSUD.
                    |
                    | Garis antara baris 10 dan 11,
                    | 11 dan 12,
                    | 12 dan 13,
                    | dst.
                    |
                    | SEMUANYA MEDIUM / TEBAL.
                    |
                    */

                    for (
                        $row = $dataStartRow;
                        $row <= $lastRow;
                        $row++
                    ) {

                        $sheet->getStyle(
                            "A{$row}:L{$row}"
                        )->applyFromArray([
                            'borders' => [

                                'top' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],

                                'bottom' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],
                            ],
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | BARIS SELANG-SELING
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = $dataStartRow;
                        $row <= $lastRow;
                        $row++
                    ) {

                        if (($row - $dataStartRow) % 2 === 1) {

                            $sheet->getStyle(
                                "A{$row}:L{$row}"
                            )->getFill()->setFillType(
                                Fill::FILL_SOLID
                            );

                            $sheet->getStyle(
                                "A{$row}:L{$row}"
                            )->getFill()->getStartColor()
                                ->setRGB($blueSoft);
                        }

                        $sheet->getRowDimension($row)
                            ->setRowHeight(24);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | GARIS LUAR TABEL
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "A{$headerRow}:A{$lastRow}"
                    )->applyFromArray([
                        'borders' => [
                            'left' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],
                        ],
                    ]);

                    $sheet->getStyle(
                        "L{$headerRow}:L{$lastRow}"
                    )->applyFromArray([
                        'borders' => [
                            'right' => [
                                'borderStyle' => Border::BORDER_MEDIUM,
                                'color' => [
                                    'rgb' => $blueDark,
                                ],
                            ],
                        ],
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | FORMAT RUPIAH
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "I{$dataStartRow}:K{$lastRow}"
                    )
                        ->getNumberFormat()
                        ->setFormatCode('"Rp" #,##0');

                    $sheet->getStyle(
                        "I{$dataStartRow}:K{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_RIGHT
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | TANGGAL
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "C{$dataStartRow}:C{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    $sheet->getStyle(
                        "F{$dataStartRow}:G{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | NOMOR
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "A{$dataStartRow}:B{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | STATUS
                    |--------------------------------------------------------------------------
                    */

                    $sheet->getStyle(
                        "L{$dataStartRow}:L{$lastRow}"
                    )
                        ->getAlignment()
                        ->setHorizontal(
                            Alignment::HORIZONTAL_CENTER
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | WARNA STATUS
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = $dataStartRow;
                        $row <= $lastRow;
                        $row++
                    ) {

                        $status = $sheet
                            ->getCell("L{$row}")
                            ->getValue();

                        if ($status === 'Selesai') {

                            $sheet->getStyle("L{$row}")
                                ->applyFromArray([

                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'DDF5E8',
                                        ],
                                    ],

                                    'font' => [
                                        'bold' => true,
                                        'color' => [
                                            'rgb' => '087443',
                                        ],
                                    ],
                                ]);

                        } elseif ($status === 'Tiba di Klinik') {

                            $sheet->getStyle("L{$row}")
                                ->applyFromArray([

                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'FFF1D6',
                                        ],
                                    ],

                                    'font' => [
                                        'bold' => true,
                                        'color' => [
                                            'rgb' => '9A6200',
                                        ],
                                    ],
                                ]);

                        } elseif ($status === 'Belum Tiba') {

                            $sheet->getStyle("L{$row}")
                                ->applyFromArray([

                                    'fill' => [
                                        'fillType' => Fill::FILL_SOLID,
                                        'startColor' => [
                                            'rgb' => 'DDEEFF',
                                        ],
                                    ],

                                    'font' => [
                                        'bold' => true,
                                        'color' => [
                                            'rgb' => $blue,
                                        ],
                                    ],
                                ]);
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | PAKSA SEMUA GARIS VERTIKAL TETAP TEBAL
                    |--------------------------------------------------------------------------
                    */

                    foreach ($columns as $column) {

                        $sheet->getStyle(
                            "{$column}{$headerRow}:{$column}{$lastRow}"
                        )->applyFromArray([
                            'borders' => [
                                'left' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],

                                'right' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],
                            ],
                        ]);
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | PAKSA SEMUA GARIS HORIZONTAL TETAP TEBAL
                    |--------------------------------------------------------------------------
                    */

                    for (
                        $row = $headerRow;
                        $row <= $lastRow;
                        $row++
                    ) {

                        $sheet->getStyle(
                            "A{$row}:L{$row}"
                        )->applyFromArray([
                            'borders' => [
                                'top' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],

                                'bottom' => [
                                    'borderStyle' => Border::BORDER_MEDIUM,
                                    'color' => [
                                        'rgb' => $blueDark,
                                    ],
                                ],
                            ],
                        ]);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | TIDAK ADA AUTOFILTER
                |--------------------------------------------------------------------------
                |
                | Tidak menggunakan setAutoFilter()
                | agar tombol dropdown tidak muncul.
                |
                */

                /*
                |--------------------------------------------------------------------------
                | FREEZE HEADER
                |--------------------------------------------------------------------------
                */

                $sheet->freezePane('A10');

                /*
                |--------------------------------------------------------------------------
                | CATATAN
                |--------------------------------------------------------------------------
                */

                $footerRow = $lastRow + 3;

                $sheet->mergeCells(
                    "A{$footerRow}:H{$footerRow}"
                );

                $sheet->setCellValue(
                    "A{$footerRow}",
                    'Catatan: Data laporan mengikuti filter yang dipilih.'
                );

                $sheet->getStyle(
                    "A{$footerRow}:H{$footerRow}"
                )->applyFromArray([

                    'font' => [
                        'italic' => true,
                        'size' => 9,
                        'color' => [
                            'rgb' => '607D8B',
                        ],
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | TANGGAL CETAK
                |--------------------------------------------------------------------------
                */

                $tanggalCetak = date('d/m/Y');

                $sheet->mergeCells(
                    "J{$footerRow}:L{$footerRow}"
                );

                $sheet->setCellValue(
                    "J{$footerRow}",
                    "Rogojampi, {$tanggalCetak}"
                );

                $sheet->getStyle(
                    "J{$footerRow}:L{$footerRow}"
                )->applyFromArray([

                    'font' => [
                        'size' => 9,
                        'color' => [
                            'rgb' => $text,
                        ],
                    ],

                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_RIGHT,
                    ],
                ]);

                /*
                |--------------------------------------------------------------------------
                | LEBAR KOLOM
                |--------------------------------------------------------------------------
                */

                $widths = [
                    'A' => 6,
                    'B' => 17,
                    'C' => 13,
                    'D' => 24,
                    'E' => 27,
                    'F' => 14,
                    'G' => 18,
                    'H' => 19,
                    'I' => 17,
                    'J' => 17,
                    'K' => 18,
                    'L' => 17,
                ];

                foreach ($widths as $column => $width) {

                    $sheet->getColumnDimension($column)
                        ->setWidth($width);
                }

                /*
                |--------------------------------------------------------------------------
                | PRINT A4 LANDSCAPE
                |--------------------------------------------------------------------------
                */

                $sheet->getPageSetup()
                    ->setOrientation(
                        PageSetup::ORIENTATION_LANDSCAPE
                    )
                    ->setPaperSize(
                        PageSetup::PAPERSIZE_A4
                    )
                    ->setFitToWidth(1)
                    ->setFitToHeight(0);

                $sheet->getPageMargins()
                    ->setTop(0.4)
                    ->setRight(0.4)
                    ->setLeft(0.4)
                    ->setBottom(0.4);

                /*
                |--------------------------------------------------------------------------
                | FONT
                |--------------------------------------------------------------------------
                */

                $sheet->getStyle("A1:L{$lastRow}")
                    ->getFont()
                    ->setName('Calibri');
            },
        ];
    }
}