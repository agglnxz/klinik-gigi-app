<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\PemesananWebController;
use App\Models\JenisGigi;
use App\Models\Laboratorium;
use App\Models\Notifikasi;
use App\Models\Pemeriksaan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class PemesananWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index displays view and calculates dashboard statistics.
     */
    public function test_index_displays_view_with_stats(): void
    {
        // Arrange
        Pemesanan::factory()->create(['status_pemesanan' => 'dalam_proses']);
        Pemesanan::factory()->create(['status_pemesanan' => 'selesai']);

        $request = Request::create('/pemesanan', 'GET');
        $controller = new PemesananWebController();

        // Act
        $response = $controller->index($request);

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.index', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('data', $data);
        self::assertArrayHasKey('totalPesanan', $data);
        self::assertArrayHasKey('sedangDiproses', $data);
        self::assertArrayHasKey('pesananSelesai', $data);
    }

    /**
     * Test show returns pemesanan.show view.
     */
    public function test_show_returns_view(): void
    {
        $pemesanan = Pemesanan::factory()->create();
        $controller = new PemesananWebController();

        $response = $controller->show($pemesanan->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.show', $response->name());
        self::assertTrue($response->getData()['data']->is($pemesanan));
    }

    /**
     * Test invoice groups items by dental type and returns invoice view.
     */
    public function test_invoice_returns_view_with_grouped_items(): void
    {
        $pemesanan = Pemesanan::factory()->create();
        $gigi = JenisGigi::factory()->create(['nama_jenis' => 'Akrilik Mahkota']);
        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi->id,
        ]);

        $controller = new PemesananWebController();
        $response = $controller->invoice($pemesanan->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.invoice', $response->name());

        $grouped = $response->getData()['groupedItems'];
        self::assertTrue($grouped->has('Akrilik Mahkota'));
    }

    /**
     * Test create auto-generates order number prefix PSN-YYYYMMDD-001.
     */
    public function test_create_generates_auto_numbering(): void
    {
        $controller = new PemesananWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.create', $response->name());

        $prefix = now()->format('Ymd');
        self::assertStringStartsWith('PSN-' . $prefix, $response->getData()['no_pemesanan']);
    }

    /**
     * Test store calculates subtotal, discount, and saves patient price correctly.
     */
    public function test_store_calculates_price_with_discount_and_creates_items(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi1 = JenisGigi::factory()->create(['estimasi_biaya' => 500000]);
        $gigi2 = JenisGigi::factory()->create(['estimasi_biaya' => 500000]);

        // Biaya lab = 200.000, Total Gigi = 1.000.000 -> Subtotal = 1.200.000
        // Diskon 10% = 120.000 -> Harga Pasien = 1.080.000
        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-20261025-777',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '200.000', // Contains dots to test string cleaning
            'diskon'           => 10,
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi1->id, $gigi2->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemesanan.index'), $response->getTargetUrl());

        $pemesanan = Pemesanan::where('no_pemesanan', 'PSN-20261025-777')->first();
        self::assertNotNull($pemesanan);
        self::assertEquals(200000, (float) $pemesanan->biaya_lab);
        self::assertEquals(1080000, (float) $pemesanan->harga_pasien);
        self::assertSame(2, PemesananItem::where('id_pemesanan', $pemesanan->id)->count());
    }

    /**
     * Test updateStatus modifies order status.
     */
    public function test_update_status_updates_record(): void
    {
        $pemesanan = Pemesanan::factory()->create([
            'status_pemesanan' => 'dalam_proses',
            'status_bayar_lab' => 'belum_lunas',
        ]);

        $request = Request::create("/pemesanan/{$pemesanan->id}/status", 'PATCH', [
            'status_pemesanan' => 'tiba_di_klinik',
            'status_bayar_lab' => 'sudah_lunas',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();
        $response = $controller->updateStatus($request, $pemesanan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        $pemesanan->refresh();
        self::assertSame('tiba_di_klinik', $pemesanan->status_pemesanan);
        self::assertSame('sudah_lunas', $pemesanan->status_bayar_lab);
    }

    /**
     * Test edit forbids non-direktur users.
     */
    public function test_edit_forbids_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $pemesanan = Pemesanan::factory()->create();
        $controller = new PemesananWebController();

        $response = $controller->edit($pemesanan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemesanan.index'), $response->getTargetUrl());
        self::assertTrue(session()->has('error'));
    }

    /**
     * Test edit allows Direktur user.
     */
    public function test_edit_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pemesanan = Pemesanan::factory()->create();
        $controller = new PemesananWebController();

        $response = $controller->edit($pemesanan->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.update', $response->name());
    }

    /**
     * Test update recalculates price, updates items, and cleans up notifications if status is completed.
     */
    public function test_update_recalculates_and_cleans_notifications(): void
    {
        // Arrange
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pemesanan = Pemesanan::factory()->create(['status_pemesanan' => 'dalam_proses']);
        Notifikasi::factory()->create(['pemesanan_id' => $pemesanan->id]);

        $gigiBaru = JenisGigi::factory()->create(['estimasi_biaya' => 800000]);

        $request = Request::create("/pemesanan/{$pemesanan->id}", 'PUT', [
            'no_pemesanan'     => $pemesanan->no_pemesanan,
            'id_pemeriksaan'   => $pemesanan->id_pemeriksaan,
            'id_lab'           => $pemesanan->id_lab,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'diskon'           => 0,
            'status_bayar_lab' => 'sudah_lunas',
            'status_pemesanan' => 'selesai', // trigger notification cleanup
            'items'            => [$gigiBaru->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->update($request, $pemesanan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemesanan.index'), $response->getTargetUrl());

        $pemesanan->refresh();
        self::assertSame('selesai', $pemesanan->status_pemesanan);
        self::assertEquals(900000, (float) $pemesanan->harga_pasien);

        // Notifications should be deleted
        self::assertSame(0, Notifikasi::where('pemesanan_id', $pemesanan->id)->count());

        // Pivot items updated
        self::assertSame(1, PemesananItem::where('id_pemesanan', $pemesanan->id)->count());
        self::assertSame($gigiBaru->id, PemesananItem::where('id_pemesanan', $pemesanan->id)->first()->id_jenis_gigi);
    }

    /**
     * Test destroy allows Direktur and rejects non-direktur.
     */
    public function test_destroy_authorization(): void
    {
        $pemesanan = Pemesanan::factory()->create();
        $controller = new PemesananWebController();

        // 1. Non-direktur rejected
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);
        $resNonDirektur = $controller->destroy($pemesanan->id);
        self::assertInstanceOf(RedirectResponse::class, $resNonDirektur);
        self::assertNotSoftDeleted('pemesanan', ['id' => $pemesanan->id]);

        // 2. Direktur allowed
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);
        $resDirektur = $controller->destroy($pemesanan->id);
        self::assertInstanceOf(RedirectResponse::class, $resDirektur);
        self::assertSoftDeleted('pemesanan', ['id' => $pemesanan->id]);
    }

    /**
     * Test pemesananRiwayat returns view with completed and arrived orders.
     */
    public function test_pemesanan_riwayat_returns_view(): void
    {
        Pemesanan::factory()->create(['status_pemesanan' => 'selesai']);
        Pemesanan::factory()->create(['status_pemesanan' => 'tiba_di_klinik']);

        $request = Request::create('/pemesanan/riwayat', 'GET');
        $controller = new PemesananWebController();

        $response = $controller->pemesananRiwayat($request);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('riwayat_pemesanan.index', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('riwayat', $data);
        self::assertArrayHasKey('totalPesanan', $data);
        self::assertArrayHasKey('telahTiba', $data);
        self::assertArrayHasKey('pesananSelesai', $data);
    }
}
