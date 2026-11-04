<?php

namespace Tests\Unit\Models;

use App\Models\JenisGigi;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisGigiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create jenis gigi with fillable attributes.
     */
    public function test_jenis_gigi_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'kode_gigi'      => 'GIGI-001',
            'nama_jenis'     => 'Gigi Tiruan Akrilik Lengkap',
            'estimasi_biaya' => 1250000,
            'is_aktif'       => true,
        ];

        // Act
        $jenis = JenisGigi::create($data);

        // Assert
        self::assertInstanceOf(JenisGigi::class, $jenis);
        self::assertSame('jenis_gigi', $jenis->getTable());
        self::assertSame('GIGI-001', $jenis->kode_gigi);
        self::assertSame('Gigi Tiruan Akrilik Lengkap', $jenis->nama_jenis);
        self::assertEquals(1250000, $jenis->estimasi_biaya);
        self::assertTrue((bool) $jenis->is_aktif);
    }

    /**
     * Test soft delete on JenisGigi model.
     */
    public function test_jenis_gigi_supports_soft_deletes(): void
    {
        // Arrange
        $jenis = JenisGigi::factory()->create();
        $id = $jenis->id;

        // Act
        $jenis->delete();

        // Assert
        self::assertSoftDeleted('jenis_gigi', ['id' => $id]);
        self::assertNull(JenisGigi::find($id));
        self::assertNotNull(JenisGigi::withTrashed()->find($id));
    }
}
