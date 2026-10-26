<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\JenisGigiWebController;
use App\Models\JenisGigi;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class JenisGigiWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with jenis_gigi list.
     */
    public function test_index_returns_view(): void
    {
        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.index', $response->name());
        self::assertTrue($response->getData()['jenis_gigi']->contains('id', $jenis->id));
    }

    /**
     * Test create returns view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new JenisGigiWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.create', $response->name());
    }

    /**
     * Test store creates jenis gigi.
     */
    public function test_store_creates_jenis_gigi(): void
    {
        $request = Request::create('/jenis-gigi', 'POST', [
            'kode_gigi'      => 'GIGI-VALPLAST-01',
            'nama_jenis'     => 'Gigi Tiruan Valplast Fleksibel',
            'estimasi_biaya' => 2000000,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('jenis-gigi.index'), $response->getTargetUrl());
        self::assertDatabaseHas('jenis_gigi', [
            'kode_gigi'      => 'GIGI-VALPLAST-01',
            'nama_jenis'     => 'Gigi Tiruan Valplast Fleksibel',
            'estimasi_biaya' => 2000000,
            'is_aktif'       => 1,
        ]);
    }

    /**
     * Test edit returns update view.
     */
    public function test_edit_returns_view(): void
    {
        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->edit($jenis->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.jenis_gigi.update', $response->name());
        self::assertTrue($response->getData()['jenisGigi']->is($jenis));
    }

    /**
     * Test update modifies jenis gigi.
     */
    public function test_update_modifies_jenis_gigi(): void
    {
        $jenis = JenisGigi::factory()->create();
        $request = Request::create("/jenis-gigi/{$jenis->id}", 'PUT', [
            'kode_gigi'      => $jenis->kode_gigi,
            'nama_jenis'     => 'Nama Baru Jenis Gigi',
            'estimasi_biaya' => 3500000,
            'is_aktif'       => 0,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new JenisGigiWebController();
        $response = $controller->update($request, $jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('jenis-gigi.index'), $response->getTargetUrl());
        self::assertDatabaseHas('jenis_gigi', [
            'id'             => $jenis->id,
            'nama_jenis'     => 'Nama Baru Jenis Gigi',
            'estimasi_biaya' => 3500000,
            'is_aktif'       => 0,
        ]);
    }

    /**
     * Test destroy allows Direktur to delete.
     */
    public function test_destroy_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->destroy($jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('jenis_gigi', ['id' => $jenis->id]);
    }

    /**
     * Test destroy rejects non-direktur.
     */
    public function test_destroy_rejects_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $jenis = JenisGigi::factory()->create();
        $controller = new JenisGigiWebController();

        $response = $controller->destroy($jenis->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('jenis_gigi', ['id' => $jenis->id]);
        self::assertTrue(session()->has('error'));
    }
}
