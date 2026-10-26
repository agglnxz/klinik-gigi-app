<?php

namespace Tests\Unit\Models;

use App\Models\Laboratorium;
use App\Models\Pemeriksaan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PemesananTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create pemesanan with relations.
     */
    public function test_pemesanan_can_be_created_with_fillable_and_relations(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();

        // Act
        $pemesanan = Pemesanan::create([
            'no_pemesanan'     => 'PSN-20261025-001',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => 200000,
            'harga_pasien'     => 600000,
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'diskon'           => 10,
        ]);

        // Assert
        self::assertInstanceOf(Pemesanan::class, $pemesanan);
        self::assertSame('pemesanan', $pemesanan->getTable());
        self::assertTrue($pemesanan->pemeriksaan->is($pemeriksaan));
        self::assertTrue($pemesanan->lab->is($lab));
        self::assertInstanceOf(Carbon::class, $pemesanan->tanggal_dikirim);
        self::assertInstanceOf(Carbon::class, $pemesanan->estimasi_selesai);
    }

    /**
     * Test items relationship (hasMany PemesananItem).
     */
    public function test_pemesanan_has_many_items(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();
        $gigi1 = \App\Models\JenisGigi::factory()->create();
        $gigi2 = \App\Models\JenisGigi::factory()->create();

        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi1->id,
        ]);
        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi2->id,
        ]);

        // Act
        $items = $pemesanan->items;

        // Assert
        self::assertCount(2, $items);
        self::assertInstanceOf(PemesananItem::class, $items->first());
    }

    /**
     * DataProvider untuk pengujian accessor is_terlambat.
     *
     * @return array<string, array{string, int, bool}>
     */
    public static function dataStatusTerlambat(): array
    {
        return [
            'dalam_proses_dan_lewat_deadline' => ['dalam_proses', -1, true],
            'dalam_proses_dan_hari_ini'        => ['dalam_proses', 0, false],
            'dalam_proses_dan_masih_ada_waktu' => ['dalam_proses', 2, false],
            'selesai_meskipun_lewat_deadline'  => ['selesai', -2, false],
            'tiba_di_klinik_lewat_deadline'    => ['tiba_di_klinik', -3, false],
        ];
    }

    /**
     * Test accessor is_terlambat menggunakan DataProvider.
     */
    #[DataProvider('dataStatusTerlambat')]
    public function test_accessor_is_terlambat(string $status, int $offsetDays, bool $expected): void
    {
        // Arrange
        $estimasi = Carbon::today()->addDays($offsetDays)->toDateString();
        $pemesanan = Pemesanan::factory()->create([
            'status_pemesanan' => $status,
            'estimasi_selesai' => $estimasi,
        ]);

        // Act
        $actual = $pemesanan->is_terlambat;

        // Assert
        self::assertSame($expected, $actual);
    }

    /**
     * Test scopeHampirJatuhTempo filters records correctly.
     */
    public function test_scope_hampir_jatuh_tempo(): void
    {
        // Arrange
        // 1. Termasuk: dalam_proses dan estimasi H+2
        $p1 = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'estimasi_selesai' => Carbon::today()->addDays(2),
        ]);
        // 2. Termasuk: dalam_proses dan estimasi hari ini
        $p2 = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'estimasi_selesai' => Carbon::today(),
        ]);
        // 3. Tidak Termasuk: dalam_proses tapi estimasi H+5 (> H+3)
        $p3 = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'estimasi_selesai' => Carbon::today()->addDays(5),
        ]);
        // 4. Tidak Termasuk: estimasi H+1 tapi status 'selesai'
        $p4 = Pemesanan::factory()->create([
            'status_pemesanan' => 'selesai',
            'estimasi_selesai' => Carbon::today()->addDays(1),
        ]);

        // Act
        $hasil = Pemesanan::hampirJatuhTempo()->pluck('id')->all();

        // Assert
        self::assertContains($p1->id, $hasil);
        self::assertContains($p2->id, $hasil);
        self::assertNotContains($p3->id, $hasil);
        self::assertNotContains($p4->id, $hasil);
    }

    /**
     * Test soft delete on Pemesanan model.
     */
    public function test_pemesanan_supports_soft_deletes(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();
        $id = $pemesanan->id;

        // Act
        $pemesanan->delete();

        // Assert
        self::assertSoftDeleted('pemesanan', ['id' => $id]);
        self::assertNull(Pemesanan::find($id));
        self::assertNotNull(Pemesanan::withTrashed()->find($id));
    }
}
