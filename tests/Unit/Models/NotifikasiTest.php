<?php

namespace Tests\Unit\Models;

use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotifikasiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test create notifikasi and belongsTo Pemesanan relation.
     */
    public function test_notifikasi_belongs_to_pemesanan(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();

        // Act
        $notifikasi = Notifikasi::create([
            'pemesanan_id' => $pemesanan->id,
            'pesan'        => 'akan segera tiba 2 hari lagi',
            'status'       => 'segera_tiba',
        ]);

        // Assert
        self::assertInstanceOf(Notifikasi::class, $notifikasi);
        self::assertSame('notifikasi', $notifikasi->getTable());
        self::assertSame('segera_tiba', $notifikasi->status);
        self::assertTrue($notifikasi->pemesanan->is($pemesanan));
    }
}
