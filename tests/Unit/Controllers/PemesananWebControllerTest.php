<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\PemesananWebController;
use App\Models\Dokter;
use App\Models\JenisGigi;
use App\Models\Laboratorium;
use App\Models\Notifikasi;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\Pemesanan;
use App\Models\PemesananItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use PHPUnit\Framework\Attributes\DataProvider;
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

    /**
     * Test invoice view contains complete patient identity, dental items, lab, and pricing data.
     */
    public function test_invoice_provides_complete_order_data_for_print(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create([
            'no_rm'  => 'RM-INV-2026',
            'nama'   => 'Ny. Siti Aminah',
            'kontak' => '081234567899',
        ]);
        $dokter = Dokter::factory()->create(['nama' => 'drg. Budi Raharjo']);
        $pemeriksaan = Pemeriksaan::factory()->create([
            'id_pasien' => $pasien->id,
            'id_dokter' => $dokter->id,
        ]);
        $lab = Laboratorium::factory()->create(['nama_lab' => 'Laboratorium Utama Dental']);
        $gigiA = JenisGigi::factory()->create(['nama_jenis' => 'Crown Zirconia']);
        $gigiB = JenisGigi::factory()->create(['nama_jenis' => 'Inlay Porselein']);

        $pemesanan = Pemesanan::factory()->create([
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'no_pemesanan'     => 'PSN-INV-999',
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => 350000,
            'harga_pasien'     => 1500000,
            'status_bayar_lab' => 'sudah_lunas',
            'status_pemesanan' => 'tiba_di_klinik',
        ]);

        PemesananItem::create(['id_pemesanan' => $pemesanan->id, 'id_jenis_gigi' => $gigiA->id]);
        PemesananItem::create(['id_pemesanan' => $pemesanan->id, 'id_jenis_gigi' => $gigiA->id]);
        PemesananItem::create(['id_pemesanan' => $pemesanan->id, 'id_jenis_gigi' => $gigiB->id]);

        $controller = new PemesananWebController();

        // Act
        $response = $controller->invoice($pemesanan->id);

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemesanan.invoice', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('data', $data);
        self::assertArrayHasKey('groupedItems', $data);

        $orderData = $data['data'];
        // Identitas pasien & dokter
        self::assertSame('RM-INV-2026', $orderData->pemeriksaan->pasien->no_rm);
        self::assertSame('Ny. Siti Aminah', $orderData->pemeriksaan->pasien->nama);
        self::assertSame('081234567899', $orderData->pemeriksaan->pasien->kontak);
        self::assertSame('drg. Budi Raharjo', $orderData->pemeriksaan->dokter->nama);

        // Data laboratorium & tanggal
        self::assertSame('Laboratorium Utama Dental', $orderData->lab->nama_lab);
        self::assertSame('2026-10-25', Carbon::parse($orderData->tanggal_dikirim)->format('Y-m-d'));
        self::assertSame('2026-10-30', Carbon::parse($orderData->estimasi_selesai)->format('Y-m-d'));

        // Biaya lab, harga pasien, status pembayaran & pemesanan
        self::assertEquals(350000.0, (float) $orderData->biaya_lab);
        self::assertEquals(1500000.0, (float) $orderData->harga_pasien);
        self::assertSame('sudah_lunas', $orderData->status_bayar_lab);
        self::assertSame('tiba_di_klinik', $orderData->status_pemesanan);

        // Verifikasi rincian grouped items
        $grouped = $data['groupedItems'];
        self::assertTrue($grouped->has('Crown Zirconia'));
        self::assertCount(2, $grouped->get('Crown Zirconia'));
        self::assertTrue($grouped->has('Inlay Porselein'));
        self::assertCount(1, $grouped->get('Inlay Porselein'));
    }

    /**
     * Test store fails and does not save data when pemeriksaan is missing or invalid.
     */
    public function test_store_fails_without_pemeriksaan_and_does_not_save_data(): void
    {
        $lab = Laboratorium::factory()->create();
        $gigi = JenisGigi::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-001',
            'id_pemeriksaan'   => 99999, // non-existent
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for non-existent id_pemeriksaan');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('id_pemeriksaan', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store fails and does not save data when items array is missing or empty.
     */
    public function test_store_fails_without_items_and_does_not_save_data(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-002',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [], // empty items
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for empty items array');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('items', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store fails and does not save data when lab ID is invalid.
     */
    public function test_store_fails_with_invalid_lab_id_and_does_not_save_data(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $gigi = JenisGigi::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-003',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => 88888, // non-existent lab
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for invalid id_lab');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('id_lab', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store fails and does not save data when jenis gigi ID in items is invalid.
     */
    public function test_store_fails_with_invalid_jenis_gigi_id_and_does_not_save_data(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-004',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [99999], // non-existent dental type
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for invalid items.*');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('items.0', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store fails and does not save data when date formats are invalid.
     */
    public function test_store_fails_with_invalid_dates_and_does_not_save_data(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi = JenisGigi::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-005',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => 'bukan-format-tanggal',
            'estimasi_selesai' => 'invalid-date',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for invalid date fields');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('tanggal_dikirim', $e->errors());
            self::assertArrayHasKey('estimasi_selesai', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store fails and does not save data when status values are outside allowed list.
     */
    public function test_store_fails_with_invalid_status_enum_and_does_not_save_data(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi = JenisGigi::factory()->create();

        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-FAIL-006',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100000',
            'status_bayar_lab' => 'kredit_cicilan', // invalid enum
            'status_pemesanan' => 'tuntas_selamanya', // invalid enum
            'items'            => [$gigi->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for invalid enum status');
        } catch (ValidationException $e) {
            self::assertArrayHasKey('status_bayar_lab', $e->errors());
            self::assertArrayHasKey('status_pemesanan', $e->errors());
        }

        self::assertDatabaseCount('pemesanan', 0);
    }

    /**
     * Test store price calculation with single item, 0% discount, and lab fee.
     */
    public function test_store_calculates_single_item_zero_discount_with_lab_fee(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi = JenisGigi::factory()->create(['estimasi_biaya' => 500000]);

        // Item 500.000 + Lab 100.000, diskon 0% -> Total 600.000
        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-CALC-SINGLE',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '100.000',
            'diskon'           => 0,
            'status_bayar_lab' => 'belum_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        $order = Pemesanan::where('no_pemesanan', 'PSN-CALC-SINGLE')->first();
        self::assertNotNull($order);
        self::assertEquals(100000.0, (float) $order->biaya_lab);
        self::assertEquals(600000.0, (float) $order->harga_pasien);
    }

    /**
     * Test store price calculation with multiple items and 100% discount.
     */
    public function test_store_calculates_multiple_items_with_discount_100_percent(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi1 = JenisGigi::factory()->create(['estimasi_biaya' => 600000]);
        $gigi2 = JenisGigi::factory()->create(['estimasi_biaya' => 400000]);

        // Total gigi 1.000.000 + Lab 200.000 = 1.200.000, Diskon 100% -> Total 0
        $request = Request::create('/pemesanan', 'POST', [
            'no_pemesanan'     => 'PSN-CALC-FREE',
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '200.000',
            'diskon'           => 100,
            'status_bayar_lab' => 'sudah_lunas',
            'status_pemesanan' => 'dalam_proses',
            'items'            => [$gigi1->id, $gigi2->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        $order = Pemesanan::where('no_pemesanan', 'PSN-CALC-FREE')->first();
        self::assertNotNull($order);
        self::assertEquals(200000.0, (float) $order->biaya_lab);
        self::assertEquals(0.0, (float) $order->harga_pasien);
        self::assertSame(2, PemesananItem::where('id_pemesanan', $order->id)->count());
    }

    /**
     * Test updateStatus updates only order and payment statuses, leaving order details untouched.
     */
    public function test_update_status_does_not_alter_order_details(): void
    {
        // Arrange
        $pemeriksaan = Pemeriksaan::factory()->create();
        $lab = Laboratorium::factory()->create();
        $gigi = JenisGigi::factory()->create();

        $pemesanan = Pemesanan::factory()->create([
            'id_pemeriksaan'   => $pemeriksaan->id,
            'id_lab'           => $lab->id,
            'no_pemesanan'     => 'PSN-STATIC-001',
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => 250000,
            'harga_pasien'     => 850000,
            'status_pemesanan' => 'dalam_proses',
            'status_bayar_lab' => 'belum_lunas',
        ]);

        PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $gigi->id,
        ]);

        $request = Request::create("/pemesanan/{$pemesanan->id}/status", 'PATCH', [
            'status_pemesanan' => 'tiba_di_klinik',
            'status_bayar_lab' => 'sudah_lunas',
            // Even if an attacker tries to pass altered amounts via status endpoint:
            'harga_pasien'     => 10000,
            'biaya_lab'        => 5000,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->updateStatus($request, $pemesanan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        $pemesanan->refresh();

        // Statuses are updated
        self::assertSame('tiba_di_klinik', $pemesanan->status_pemesanan);
        self::assertSame('sudah_lunas', $pemesanan->status_bayar_lab);

        // Core order details must NOT be modified
        self::assertSame('PSN-STATIC-001', $pemesanan->no_pemesanan);
        self::assertSame('2026-10-25', Carbon::parse($pemesanan->tanggal_dikirim)->format('Y-m-d'));
        self::assertSame('2026-10-30', Carbon::parse($pemesanan->estimasi_selesai)->format('Y-m-d'));
        self::assertEquals(250000.0, (float) $pemesanan->biaya_lab);
        self::assertEquals(850000.0, (float) $pemesanan->harga_pasien);
        self::assertSame(1, PemesananItem::where('id_pemesanan', $pemesanan->id)->count());
    }

    /**
     * Data provider untuk role non-direktur (Admin dan Marketing).
     *
     * @return array<string, array{string}>
     */
    public static function nonDirekturRoles(): array
    {
        return [
            'role_admin'     => ['Admin'],
            'role_marketing' => ['Marketing'],
        ];
    }

    /**
     * Test update action is forbidden for Admin and Marketing roles.
     */
    #[DataProvider('nonDirekturRoles')]
    public function test_update_forbids_non_direktur_roles(string $role): void
    {
        // Arrange
        $user = User::factory()->create(['role' => $role]);
        Auth::login($user);

        $pemesanan = Pemesanan::factory()->create([
            'no_pemesanan' => 'PSN-SECURE-001',
            'biaya_lab'    => 200000,
            'harga_pasien' => 700000,
        ]);

        $request = Request::create("/pemesanan/{$pemesanan->id}", 'PUT', [
            'no_pemesanan'     => 'PSN-SECURE-001',
            'id_pemeriksaan'   => $pemesanan->id_pemeriksaan,
            'id_lab'           => $pemesanan->id_lab,
            'tanggal_dikirim'  => '2026-10-25',
            'estimasi_selesai' => '2026-10-30',
            'biaya_lab'        => '999999',
            'status_bayar_lab' => 'sudah_lunas',
            'status_pemesanan' => 'selesai',
            'items'            => [JenisGigi::factory()->create()->id],
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemesananWebController();

        // Act
        $response = $controller->update($request, $pemesanan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemesanan.index'), $response->getTargetUrl());
        self::assertTrue(session()->has('error'));

        // Database record remains unchanged
        $pemesanan->refresh();
        self::assertEquals(200000.0, (float) $pemesanan->biaya_lab);
        self::assertEquals(700000.0, (float) $pemesanan->harga_pasien);
    }

    /**
     * Test edit action is forbidden for Marketing role.
     */
    public function test_edit_forbids_marketing_role(): void
    {
        $marketing = User::factory()->create(['role' => 'Marketing']);
        Auth::login($marketing);

        $pemesanan = Pemesanan::factory()->create();
        $controller = new PemesananWebController();

        $response = $controller->edit($pemesanan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemesanan.index'), $response->getTargetUrl());
        self::assertTrue(session()->has('error'));
    }
}
