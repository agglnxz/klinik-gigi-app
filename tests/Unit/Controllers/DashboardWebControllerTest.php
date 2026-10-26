<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\DashboardWebController;
use App\Models\Pasien;
use App\Models\Pemesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\View\View;
use Tests\TestCase;

class DashboardWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dashboard calculates statistics correctly and passes them to view.
     */
    public function test_dashboard_computes_statistics_and_returns_view(): void
    {
        // Arrange
        Pasien::factory()->count(3)->create();

        Pemesanan::factory()->create(['status_pemesanan' => 'dalam_proses']);
        Pemesanan::factory()->create(['status_pemesanan' => 'dalam_proses']);
        Pemesanan::factory()->create(['status_pemesanan' => 'tiba_di_klinik']);
        Pemesanan::factory()->create(['status_pemesanan' => 'selesai']);

        $controller = new DashboardWebController();

        // Act
        $response = $controller->dashboard();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('dashboard', $response->name());

        $data = $response->getData();
        self::assertSame(Pasien::count(), $data['totalPasien']);
        self::assertSame(2, $data['pesananProses']);
        self::assertSame(1, $data['pesananSelesai']);
        self::assertSame(2, $data['pesananBelumTiba']);
        self::assertSame(2, $data['pesananSudahTiba']);
    }
}
