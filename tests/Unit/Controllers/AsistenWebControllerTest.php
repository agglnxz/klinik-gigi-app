<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\AsistenWebController;
use App\Models\Asisten;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class AsistenWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with asisten list.
     */
    public function test_index_returns_view(): void
    {
        $asisten = Asisten::factory()->create();
        $controller = new AsistenWebController();

        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.asisten.index', $response->name());
        self::assertTrue($response->getData()['asisten']->contains('id', $asisten->id));
    }

    /**
     * Test create returns view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new AsistenWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.asisten.create', $response->name());
    }

    /**
     * Test store creates asisten.
     */
    public function test_store_creates_asisten(): void
    {
        $request = Request::create('/asisten', 'POST', [
            'nama'   => 'Rina Sasmita',
            'kontak' => '087788990011',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new AsistenWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('asisten.index'), $response->getTargetUrl());
        self::assertDatabaseHas('asisten', [
            'nama'     => 'Rina Sasmita',
            'kontak'   => '087788990011',
            'is_aktif' => 1,
        ]);
    }

    /**
     * Test edit returns update view.
     */
    public function test_edit_returns_view(): void
    {
        $asisten = Asisten::factory()->create();
        $controller = new AsistenWebController();

        $response = $controller->edit($asisten->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.asisten.update', $response->name());
        self::assertTrue($response->getData()['asisten']->is($asisten));
    }

    /**
     * Test update modifies asisten.
     */
    public function test_update_modifies_asisten(): void
    {
        $asisten = Asisten::factory()->create();
        $request = Request::create("/asisten/{$asisten->id}", 'PUT', [
            'nama'     => 'Nama Baru Asisten',
            'kontak'   => '0812344321',
            'is_aktif' => 0,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new AsistenWebController();
        $response = $controller->update($request, $asisten->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('asisten.index'), $response->getTargetUrl());
        self::assertDatabaseHas('asisten', [
            'id'       => $asisten->id,
            'nama'     => 'Nama Baru Asisten',
            'is_aktif' => 0,
        ]);
    }

    /**
     * Test destroy allows Direktur to soft delete.
     */
    public function test_destroy_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $asisten = Asisten::factory()->create();
        $controller = new AsistenWebController();

        $response = $controller->destroy($asisten->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('asisten', ['id' => $asisten->id]);
    }

    /**
     * Test destroy rejects non-direktur user.
     */
    public function test_destroy_rejects_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $asisten = Asisten::factory()->create();
        $controller = new AsistenWebController();

        $response = $controller->destroy($asisten->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('asisten', ['id' => $asisten->id]);
        self::assertTrue(session()->has('error'));
    }
}
