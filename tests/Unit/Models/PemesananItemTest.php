<?php

namespace Tests\Unit\Models;

use App\Models\JenisGigi;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PemesananItemTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create item and belongsTo JenisGigi relation.
     */
    public function test_pemesanan_item_belongs_to_jenis_gigi(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();
        $jenisGigi = JenisGigi::factory()->create();

        // Act
        $item = PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $jenisGigi->id,
        ]);

        // Assert
        self::assertInstanceOf(PemesananItem::class, $item);
        self::assertSame('pemesanan_items', $item->getTable());
        self::assertTrue($item->jenisGigi->is($jenisGigi));
    }

    /**
     * Test jenisGigi relationship includes trashed records.
     */
    public function test_pemesanan_item_retrieves_soft_deleted_jenis_gigi(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();
        $jenisGigi = JenisGigi::factory()->create();
        $item = PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $jenisGigi->id,
        ]);

        // Act
        $jenisGigi->delete();
        $item->refresh();

        // Assert
        self::assertNotNull($item->jenisGigi);
        self::assertSame($jenisGigi->id, $item->jenisGigi->id);
    }
}
