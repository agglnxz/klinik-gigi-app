<?php

namespace Tests\Unit\Models;

use App\Models\Laboratorium;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaboratoriumTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create laboratorium with fillable attributes.
     */
    public function test_laboratorium_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'nama_lab' => 'Lab Dental Mandiri',
            'alamat'   => 'Jl. Gatot Subroto No. 12',
            'kontak'   => '081234112233',
            'is_aktif' => true,
        ];

        // Act
        $lab = Laboratorium::create($data);

        // Assert
        self::assertInstanceOf(Laboratorium::class, $lab);
        self::assertSame('laboratorium', $lab->getTable());
        self::assertSame('Lab Dental Mandiri', $lab->nama_lab);
        self::assertSame('Jl. Gatot Subroto No. 12', $lab->alamat);
        self::assertSame('081234112233', $lab->kontak);
        self::assertTrue((bool) $lab->is_aktif);
    }

    /**
     * Test soft delete on Laboratorium model.
     */
    public function test_laboratorium_supports_soft_deletes(): void
    {
        // Arrange
        $lab = Laboratorium::factory()->create();
        $id = $lab->id;

        // Act
        $lab->delete();

        // Assert
        self::assertSoftDeleted('laboratorium', ['id' => $id]);
        self::assertNull(Laboratorium::find($id));
        self::assertNotNull(Laboratorium::withTrashed()->find($id));
    }
}
