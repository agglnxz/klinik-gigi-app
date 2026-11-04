<?php

namespace Tests\Unit\Models;

use App\Models\PengajuanHapus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengajuanHapusTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create pengajuan hapus and relation to pemohon (User).
     */
    public function test_pengajuan_hapus_can_be_created_with_pemohon_relation(): void
    {
        // Arrange
        $user = User::factory()->create();

        // Act
        $pengajuan = PengajuanHapus::create([
            'nama_tabel'      => 'pasien',
            'id_referensi'    => '10',
            'nama_data'       => 'Pasien Demo',
            'alasan_hapus'    => 'Data salah input',
            'status_approval' => 'Pending',
            'id_pemohon'      => $user->id,
        ]);

        // Assert
        self::assertInstanceOf(PengajuanHapus::class, $pengajuan);
        self::assertSame('pengajuan_hapus', $pengajuan->getTable());
        self::assertSame('Pending', $pengajuan->status_approval);
        self::assertTrue($pengajuan->pemohon->is($user));
    }

    /**
     * Test soft delete on PengajuanHapus model.
     */
    public function test_pengajuan_hapus_supports_soft_deletes(): void
    {
        // Arrange
        $pengajuan = PengajuanHapus::factory()->create();
        $id = $pengajuan->id;

        // Act
        $pengajuan->delete();

        // Assert
        self::assertSoftDeleted('pengajuan_hapus', ['id' => $id]);
        self::assertNull(PengajuanHapus::find($id));
        self::assertNotNull(PengajuanHapus::withTrashed()->find($id));
    }
}
