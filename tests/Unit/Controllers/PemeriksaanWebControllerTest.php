<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\PemeriksaanWebController;
use App\Models\Asisten;
use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\Pemeriksaan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class PemeriksaanWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index displays view and handles search.
     */
    public function test_index_displays_view_and_handles_search(): void
    {
        $pasienA = Pasien::factory()->create(['nama' => 'Rudy Hartono']);
        $pasienB = Pasien::factory()->create(['nama' => 'Alan Budikusuma']);

        $pem1 = Pemeriksaan::factory()->create(['id_pasien' => $pasienA->id, 'no_pemeriksaan' => 'PRX-001']);
        $pem2 = Pemeriksaan::factory()->create(['id_pasien' => $pasienB->id, 'no_pemeriksaan' => 'PRX-002']);

        $request = Request::create('/pemeriksaan', 'GET', ['search' => 'Rudy']);
        $controller = new PemeriksaanWebController();

        $response = $controller->index($request);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemeriksaan.index', $response->name());

        $data = $response->getData();
        self::assertTrue($data['pemeriksaan']->contains('id', $pem1->id));
        self::assertFalse($data['pemeriksaan']->contains('id', $pem2->id));
    }

    /**
     * Test create computes automatic numbering prefix PRX-YYYYMMDD-001.
     */
    public function test_create_generates_auto_numbering(): void
    {
        $prefix = now()->format('Ymd');
        Pemeriksaan::factory()->create([
            'no_pemeriksaan' => 'PRX-' . $prefix . '-001',
        ]);

        $controller = new PemeriksaanWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemeriksaan.create', $response->name());

        $expectedNextNumber = 'PRX-' . $prefix . '-002';
        self::assertSame($expectedNextNumber, $response->getData()['no_pemeriksaan']);
    }

    /**
     * Test store creates new pemeriksaan.
     */
    public function test_store_creates_pemeriksaan(): void
    {
        $pasien = Pasien::factory()->create();
        $dokter = Dokter::factory()->create();
        $asisten = Asisten::factory()->create();

        $request = Request::create('/pemeriksaan', 'POST', [
            'no_pemeriksaan' => 'PRX-20261025-999',
            'tanggal'        => '2026-10-25',
            'catatan'        => 'Pasien mengeluh ngilu gigi bungsu',
            'id_pasien'      => $pasien->id,
            'id_dokter'      => $dokter->id,
            'id_asisten'     => $asisten->id,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemeriksaanWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemeriksaan.index'), $response->getTargetUrl());
        self::assertDatabaseHas('pemeriksaan', [
            'no_pemeriksaan' => 'PRX-20261025-999',
            'id_pasien'      => $pasien->id,
        ]);
    }

    /**
     * Test edit returns update view with relations.
     */
    public function test_edit_returns_update_view(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $controller = new PemeriksaanWebController();

        $response = $controller->edit($pemeriksaan->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pemeriksaan.update', $response->name());
        self::assertTrue($response->getData()['pemeriksaan']->is($pemeriksaan));
    }

    /**
     * Test update modifies pemeriksaan record.
     */
    public function test_update_modifies_pemeriksaan(): void
    {
        $pemeriksaan = Pemeriksaan::factory()->create();
        $dokterBaru = Dokter::factory()->create();

        $request = Request::create("/pemeriksaan/{$pemeriksaan->id}", 'PUT', [
            'no_pemeriksaan' => $pemeriksaan->no_pemeriksaan,
            'tanggal'        => '2026-10-26',
            'catatan'        => 'Catatan diperbarui setelah tindakan',
            'id_pasien'      => $pemeriksaan->id_pasien,
            'id_dokter'      => $dokterBaru->id,
            'id_asisten'     => $pemeriksaan->id_asisten,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PemeriksaanWebController();
        $response = $controller->update($request, $pemeriksaan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pemeriksaan.index'), $response->getTargetUrl());
        self::assertDatabaseHas('pemeriksaan', [
            'id'        => $pemeriksaan->id,
            'catatan'   => 'Catatan diperbarui setelah tindakan',
            'id_dokter' => $dokterBaru->id,
        ]);
    }

    /**
     * Test destroy allows Direktur to delete pemeriksaan.
     */
    public function test_destroy_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pemeriksaan = Pemeriksaan::factory()->create();
        $controller = new PemeriksaanWebController();

        $response = $controller->destroy($pemeriksaan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('pemeriksaan', ['id' => $pemeriksaan->id]);
    }

    /**
     * Test destroy rejects non-direktur.
     */
    public function test_destroy_rejects_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $pemeriksaan = Pemeriksaan::factory()->create();
        $controller = new PemeriksaanWebController();

        $response = $controller->destroy($pemeriksaan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('pemeriksaan', ['id' => $pemeriksaan->id]);
        self::assertTrue(session()->has('error'));
    }
}
