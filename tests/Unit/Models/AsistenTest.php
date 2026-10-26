<?php

namespace Tests\Unit\Models;

use App\Models\Asisten;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AsistenTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create asisten with fillable attributes.
     */
    public function test_asisten_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'nama'     => 'Dewi Lestari',
            'kontak'   => '081399887766',
            'is_aktif' => true,
        ];

        // Act
        $asisten = Asisten::create($data);

        // Assert
        self::assertInstanceOf(Asisten::class, $asisten);
        self::assertSame('asisten', $asisten->getTable());
        self::assertSame('Dewi Lestari', $asisten->nama);
        self::assertSame('081399887766', $asisten->kontak);
        self::assertTrue((bool) $asisten->is_aktif);
    }

    /**
     * Test soft delete on Asisten model.
     */
    public function test_asisten_supports_soft_deletes(): void
    {
        // Arrange
        $asisten = Asisten::factory()->create();
        $id = $asisten->id;

        // Act
        $asisten->delete();

        // Assert
        self::assertSoftDeleted('asisten', ['id' => $id]);
        self::assertNull(Asisten::find($id));
        self::assertNotNull(Asisten::withTrashed()->find($id));
    }
}
