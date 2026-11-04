<?php

namespace Tests\Unit\Models;

use App\Models\Dokter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DokterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create dokter with fillable attributes.
     */
    public function test_dokter_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'nama'     => 'drg. Siti Nurhaliza',
            'kontak'   => '082198765432',
            'is_aktif' => true,
        ];

        // Act
        $dokter = Dokter::create($data);

        // Assert
        self::assertInstanceOf(Dokter::class, $dokter);
        self::assertSame('dokter', $dokter->getTable());
        self::assertSame('drg. Siti Nurhaliza', $dokter->nama);
        self::assertSame('082198765432', $dokter->kontak);
        self::assertTrue((bool) $dokter->is_aktif);
    }

    /**
     * Test soft delete on Dokter model.
     */
    public function test_dokter_supports_soft_deletes(): void
    {
        // Arrange
        $dokter = Dokter::factory()->create();
        $id = $dokter->id;

        // Act
        $dokter->delete();

        // Assert
        self::assertSoftDeleted('dokter', ['id' => $id]);
        self::assertNull(Dokter::find($id));
        self::assertNotNull(Dokter::withTrashed()->find($id));
    }
}
