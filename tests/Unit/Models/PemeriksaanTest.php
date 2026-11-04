<?php

namespace Tests\Unit\Models;

use App\Models\Asisten;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemeriksaanTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create pemeriksaan with relations.
     */
    public function test_pemeriksaan_can_be_created_with_fillable_and_relations(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create();
        $dokter = Dokter::factory()->create();
        $asisten = Asisten::factory()->create();

        // Act
        $pemeriksaan = Pemeriksaan::create([
            'no_pemeriksaan' => 'PRX-20261025-001',
            'tanggal'        => '2026-10-25',
            'catatan'        => 'Pencabutan gigi geraham bawah',
            'id_pasien'      => $pasien->id,
            'id_dokter'      => $dokter->id,
            'id_asisten'     => $asisten->id,
        ]);

        // Assert
        self::assertInstanceOf(Pemeriksaan::class, $pemeriksaan);
        self::assertSame('pemeriksaan', $pemeriksaan->getTable());
        self::assertSame('PRX-20261025-001', $pemeriksaan->no_pemeriksaan);
        self::assertSame('Pencabutan gigi geraham bawah', $pemeriksaan->catatan);

        // Test relations
        self::assertTrue($pemeriksaan->pasien->is($pasien));
        self::assertTrue($pemeriksaan->dokter->is($dokter));
        self::assertTrue($pemeriksaan->asisten->is($asisten));
    }

    /**
     * Test relation with trashed parent (pasien, dokter, asisten).
     */
    public function test_pemeriksaan_relationships_include_trashed_records(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create();
        $dokter = Dokter::factory()->create();
        $asisten = Asisten::factory()->create();

        $pemeriksaan = Pemeriksaan::factory()->create([
            'id_pasien'  => $pasien->id,
            'id_dokter'  => $dokter->id,
            'id_asisten' => $asisten->id,
        ]);

        // Act
        $pasien->delete();
        $dokter->delete();
        $asisten->delete();

        // Reload relation
        $pemeriksaan->refresh();

        // Assert
        self::assertNotNull($pemeriksaan->pasien);
        self::assertSame($pasien->id, $pemeriksaan->pasien->id);
        self::assertNotNull($pemeriksaan->dokter);
        self::assertSame($dokter->id, $pemeriksaan->dokter->id);
        self::assertNotNull($pemeriksaan->asisten);
        self::assertSame($asisten->id, $pemeriksaan->asisten->id);
    }

    /**
     * Test soft delete on Pemeriksaan model.
     */
    public function test_pemeriksaan_supports_soft_deletes(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $id = $pemeriksaan->id;

        // Act
        $pemeriksaan->delete();

        // Assert
        self::assertSoftDeleted('pemeriksaan', ['id' => $id]);
        self::assertNull(Pemeriksaan::find($id));
        self::assertNotNull(Pemeriksaan::withTrashed()->find($id));
    }
}
