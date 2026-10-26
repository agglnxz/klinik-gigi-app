<?php

namespace Tests\Unit\Controllers;

use App\Exports\LaporanPemesananExport;
use App\Http\Controllers\Web\LaporanWebController;
use App\Models\Pemesanan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Tests\TestCase;

class LaporanWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index displays report rows and order counts.
     */
    public function test_index_returns_view_with_report_rows(): void
    {
        // Arrange
        Pemesanan::factory()->create(['status_pemesanan' => 'dalam_proses']);
        Pemesanan::factory()->create(['status_pemesanan' => 'selesai']);

        $controller = new LaporanWebController();

        // Act
        $response = $controller->index();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('laporan.index', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('pesanan', $data);
        self::assertArrayHasKey('reportRows', $data);
        self::assertSame(1, $data['pesananBelumTiba']);
        self::assertSame(1, $data['pesananSudahTiba']);
    }

    /**
     * Test export triggers Excel::download with LaporanPemesananExport.
     */
    public function test_export_triggers_excel_download(): void
    {
        // Arrange
        Excel::fake();

        $request = Request::create('/laporan/export', 'GET', [
            'search' => 'Budi',
            'dari'   => '2026-10-01',
            'sampai' => '2026-10-31',
            'status' => 'selesai',
        ]);

        $controller = new LaporanWebController();

        // Act
        $controller->export($request);

        // Assert
        Excel::assertDownloaded('laporan_pemesanan_gigi_palsu.xlsx', function (LaporanPemesananExport $export) {
            return true;
        });
    }
}
