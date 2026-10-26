<?php

namespace Tests\Unit\Models;

use App\Models\Pasien;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PasienTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create pasien with fillable attributes.
     */
    public function test_pasien_can_be_created_with_fillable_attributes(): void
    {
        // Arrange
        $data = [
            'no_rm'         => 'RM-2026-0001',
            'nama'          => 'Ahmad Dahlan',
            'kontak'        => '081234567890',
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Jl. Merdeka No. 45',
            'is_aktif'      => true,
        ];

        // Act
        $pasien = Pasien::create($data);

        // Assert
        self::assertInstanceOf(Pasien::class, $pasien);
        self::assertSame('pasien', $pasien->getTable());
        self::assertSame('RM-2026-0001', $pasien->no_rm);
        self::assertSame('Ahmad Dahlan', $pasien->nama);
        self::assertSame('081234567890', $pasien->kontak);
        self::assertSame('Laki-laki', $pasien->jenis_kelamin);
        self::assertSame('Jl. Merdeka No. 45', $pasien->alamat);
        self::assertTrue((bool) $pasien->is_aktif);
    }

    /**
     * Test soft delete on Pasien model.
     */
    public function test_pasien_supports_soft_deletes(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create();
        $id = $pasien->id;

        // Act
        $pasien->delete();

        // Assert
        self::assertSoftDeleted('pasien', ['id' => $id]);
        self::assertNull(Pasien::find($id));
        self::assertNotNull(Pasien::withTrashed()->find($id));
    }
}
