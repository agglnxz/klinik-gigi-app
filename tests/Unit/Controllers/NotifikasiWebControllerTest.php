<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\NotifikasiWebController;
use App\Models\Notifikasi;
use App\Models\Pemesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use Tests\TestCase;

class NotifikasiWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index displays notifications with eager loaded pemesanan.
     */
    public function test_index_returns_view_with_notifications(): void
    {
        // Arrange
        $pemesanan = Pemesanan::factory()->create();
        $notif = Notifikasi::factory()->create(['pemesanan_id' => $pemesanan->id]);

        $controller = new NotifikasiWebController();

        // Act
        $response = $controller->index();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('notifikasi', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('notifikasi', $data);
        self::assertTrue($data['notifikasi']->contains('id', $notif->id));
    }
}
