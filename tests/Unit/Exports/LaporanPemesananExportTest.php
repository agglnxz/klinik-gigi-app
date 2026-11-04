<?php

namespace Tests\Unit\Exports;

use App\Exports\LaporanPemesananExport;
use App\Models\JenisGigi;
use App\Models\Laboratorium;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Maatwebsite\Excel\Events\AfterSheet;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class LaporanPemesananExportTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test startCell returns 'A9'.
     */
    public function test_start_cell_returns_a9(): void
    {
        $export = new LaporanPemesananExport();
        self::assertSame('A9', $export->startCell());
    }

    /**
     * Test headings contains exactly 12 columns with expected titles.
     */
    public function test_headings_returns_twelve_expected_columns(): void
    {
        $export = new LaporanPemesananExport();
        $headings = $export->headings();

        self::assertCount(12, $headings);
        self::assertSame('No', $headings[0]);
        self::assertSame('No Pemesanan', $headings[1]);
        self::assertSame('Nama Pasien', $headings[3]);
        self::assertSame('Status', $headings[11]);
    }

    /**
     * Data provider untuk pemetaan status pemesanan ke teks laporan.
     *
     * @return array<string, array{string, string}>
     */
    public static function dataStatusLaporan(): array
    {
        return [
            'status_dalam_proses' => ['dalam_proses', 'Belum Tiba'],
            'status_tiba_di_klinik'=> ['tiba_di_klinik', 'Tiba di Klinik'],
            'status_selesai'      => ['selesai', 'Selesai'],
        ];
    }

    /**
     * Test map method formats values, items, and status text accurately.
     */
    #[DataProvider('dataStatusLaporan')]
    public function test_map_formats_row_accurately(string $dbStatus, string $expectedStatusText): void
    {
        // Arrange
        $pasien = Pasien::factory()->create(['nama' => 'Budi Santoso']);
        $pemeriksaan = Pemeriksaan::factory()->create(['id_pasien' => $pasien->id]);
        $lab = Laboratorium::factory()->create(['nama_lab' => 'Lab Mahkota']);
        $gigi1 = JenisGigi::factory()->create(['nama_jenis' => 'Gigi Tiruan Sebagian']);
        $gigi2 = JenisGigi::factory()->create(['nama_jenis' => 'Gigi Tiruan Lengkap']);

        $pemesanan = Pemesanan::factory()->create([
            'no_pemesanan'     => 'PSN-20261025-099',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => 350000,
            'harga_pasien'     => 1200000,
            'status_pemesanan' => $dbStatus,
            'created_at'       => Carbon::create(2026, 10, 25, 10, 0, 0),
        ]);

        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi1->id,
        ]);
        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi2->id,
        ]);

        // Eager load relations for map
        $pemesanan->load(['pemeriksaan.pasien', 'lab', 'items.jenisGigi']);

        $export = new LaporanPemesananExport();

        // Act
        $row = $export->map($pemesanan);

        // Assert
        self::assertCount(12, $row);
        self::assertSame(1, $row[0]); // nomor urut
        self::assertSame('PSN-20261025-099', $row[1]);
        self::assertSame('25/10/2026', $row[2]);
        self::assertSame('Budi Santoso', $row[3]);
        self::assertStringContainsString('Gigi Tiruan Sebagian', $row[4]);
        self::assertStringContainsString('Gigi Tiruan Lengkap', $row[4]);
        self::assertSame('25/10/2026', $row[5]);
        self::assertSame('30/10/2026', $row[6]);
        self::assertSame('Lab Mahkota', $row[8]);
        self::assertEquals(350000.0, $row[9]);
        self::assertEquals(1200000.0, $row[10]);
        self::assertSame($expectedStatusText, $row[11]);
    }

    /**
     * Test map handles null relationships safely without throwing exceptions.
     */
    public function test_map_handles_null_relations(): void
    {
        // Arrange
        $pemesanan = new Pemesanan([
            'no_pemesanan'     => null,
            'tanggal_dikirim'  => null,
            'estimasi_selesai' => null,
            'status_pemesanan' => 'dalam_proses',
        ]);
        $export = new LaporanPemesananExport();

        // Act
        $row = $export->map($pemesanan);

        // Assert
        self::assertSame('-', $row[1]);
        self::assertSame('Pasien Terhapus', $row[3]);
        self::assertSame('-', $row[4]); // items
        self::assertSame('-', $row[5]); // tgl kirim
        self::assertSame('-', $row[6]); // estimasi
        self::assertSame('-', $row[8]); // lab
        self::assertEquals(0.0, $row[9]); // biaya lab
        self::assertEquals(0.0, $row[10]); // harga pasien
    }

    /**
     * Test collection filters by date range, status, and search query.
     */
    public function test_collection_filters_records_correctly(): void
    {
        // Arrange
        $pasienA = Pasien::factory()->create(['nama' => 'Citra Kirana']);
        $pasienB = Pasien::factory()->create(['nama' => 'Doni Salman']);

        $pem1 = Pemesanan::factory()->create([
            'id_pemeriksaan'   => Pemeriksaan::factory()->create(['id_pasien' => $pasienA->id])->id,
            'tanggal_dikirim'  => '2026-10-01',
            'status_pemesanan' => 'dalam_proses',
        ]);

        $pem2 = Pemesanan::factory()->create([
            'id_pemeriksaan'   => Pemeriksaan::factory()->create(['id_pasien' => $pasienB->id])->id,
            'tanggal_dikirim'  => '2026-10-15',
            'status_pemesanan' => 'selesai',
        ]);

        // Act 1: Filter by search
        $exportSearch = new LaporanPemesananExport(search: 'Citra');
        $resultSearch = $exportSearch->collection();
        self::assertTrue($resultSearch->contains('id', $pem1->id));
        self::assertFalse($resultSearch->contains('id', $pem2->id));

        // Act 2: Filter by status
        $exportStatus = new LaporanPemesananExport(status: 'selesai');
        $resultStatus = $exportStatus->collection();
        self::assertFalse($resultStatus->contains('id', $pem1->id));
        self::assertTrue($resultStatus->contains('id', $pem2->id));

        // Act 3: Filter by date range
        $exportDate = new LaporanPemesananExport(dari: '2026-10-10', sampai: '2026-10-20');
        $resultDate = $exportDate->collection();
        self::assertFalse($resultDate->contains('id', $pem1->id));
        self::assertTrue($resultDate->contains('id', $pem2->id));
    }

    /**
     * Test registerEvents returns AfterSheet event listener.
     */
    public function test_register_events_returns_after_sheet(): void
    {
        $export = new LaporanPemesananExport();
        $events = $export->registerEvents();

        self::assertArrayHasKey(AfterSheet::class, $events);
        self::assertIsCallable($events[AfterSheet::class]);
    }

    /**
     * Test collection filters by boundary dates (dari == sampai).
     */
    public function test_collection_filters_with_boundary_dates(): void
    {
        // Arrange
        $pemYesterday = Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-14',
            'status_pemesanan' => 'dalam_proses',
        ]);
        $pemTargetDay = Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-15',
            'status_pemesanan' => 'dalam_proses',
        ]);
        $pemTomorrow = Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-16',
            'status_pemesanan' => 'dalam_proses',
        ]);

        // Act: dari == sampai on 2026-10-15
        $export = new LaporanPemesananExport(dari: '2026-10-15', sampai: '2026-10-15');
        $collection = $export->collection();

        // Assert: only pemTargetDay should be included
        self::assertFalse($collection->contains('id', $pemYesterday->id));
        self::assertTrue($collection->contains('id', $pemTargetDay->id));
        self::assertFalse($collection->contains('id', $pemTomorrow->id));
        self::assertSame(1, $collection->count());
    }

    /**
     * Test collection returns empty collection when filters match no data.
     */
    public function test_collection_returns_empty_when_no_match(): void
    {
        Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-15',
            'status_pemesanan' => 'dalam_proses',
        ]);

        $export = new LaporanPemesananExport(search: 'KataKunciYangPastiTidakAdaDiDatabase999');
        $collection = $export->collection();

        self::assertTrue($collection->isEmpty());
        self::assertSame(0, $collection->count());
    }

    /**
     * Test export collection verifies exact row counts, nominal mapping, and total sums.
     */
    public function test_export_verifies_exact_rows_and_financial_totals(): void
    {
        // Arrange
        $pem1 = Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-20',
            'biaya_lab'        => 150000,
            'harga_pasien'     => 600000,
            'status_pemesanan' => 'dalam_proses',
        ]);
        $pem2 = Pemesanan::factory()->create([
            'tanggal_dikirim'  => '2026-10-21',
            'biaya_lab'        => 250000,
            'harga_pasien'     => 900000,
            'status_pemesanan' => 'selesai',
        ]);

        $export = new LaporanPemesananExport(dari: '2026-10-20', sampai: '2026-10-21');
        $collection = $export->collection();

        // Assert count and financial sums
        self::assertCount(2, $collection);
        $totalNominal = $collection->sum(fn ($item) => (float) $item->getRawOriginal('harga_pasien'));
        self::assertEquals(1500000.0, $totalNominal);

        // Map rows and assert each column matches
        $row1 = $export->map($collection->firstWhere('id', $pem1->id));
        self::assertEquals(150000.0, $row1[9]); // biaya lab
        self::assertEquals(600000.0, $row1[10]); // harga pasien
        self::assertSame('Belum Tiba', $row1[11]); // status label

        $row2 = $export->map($collection->firstWhere('id', $pem2->id));
        self::assertEquals(250000.0, $row2[9]);
        self::assertEquals(900000.0, $row2[10]);
        self::assertSame('Selesai', $row2[11]);
    }
}
