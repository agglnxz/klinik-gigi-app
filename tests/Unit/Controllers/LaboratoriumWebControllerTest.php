<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\LaboratoriumWebController;
use App\Models\Laboratorium;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class LaboratoriumWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with laboratorium list.
     */
    public function test_index_returns_view(): void
    {
        $lab = Laboratorium::factory()->create();
        $controller = new LaboratoriumWebController();

        $response = $controller->index();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.laboratorium.index', $response->name());
        self::assertTrue($response->getData()['laboratorium']->contains('id', $lab->id));
    }

    /**
     * Test create returns view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new LaboratoriumWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.laboratorium.create', $response->name());
    }

    /**
     * Test store creates laboratorium.
     */
    public function test_store_creates_laboratorium(): void
    {
        $request = Request::create('/laboratorium', 'POST', [
            'nama_lab' => 'Lab Dental Sentosa',
            'alamat'   => 'Jl. Raya Rogojampi No. 8',
            'kontak'   => '081345678910',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new LaboratoriumWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('laboratorium.index'), $response->getTargetUrl());
        self::assertDatabaseHas('laboratorium', [
            'nama_lab' => 'Lab Dental Sentosa',
            'alamat'   => 'Jl. Raya Rogojampi No. 8',
            'is_aktif' => 1,
        ]);
    }

    /**
     * Test edit returns update view.
     */
    public function test_edit_returns_view(): void
    {
        $lab = Laboratorium::factory()->create();
        $controller = new LaboratoriumWebController();

        $response = $controller->edit($lab->id);

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.laboratorium.update', $response->name());
        self::assertTrue($response->getData()['laboratorium']->is($lab));
    }

    /**
     * Test update modifies laboratorium.
     */
    public function test_update_modifies_laboratorium(): void
    {
        $lab = Laboratorium::factory()->create();
        $request = Request::create("/laboratorium/{$lab->id}", 'PUT', [
            'nama_lab' => 'Lab Nama Baru',
            'alamat'   => 'Alamat Baru',
            'kontak'   => '08222333444',
            'is_aktif' => 0,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new LaboratoriumWebController();
        $response = $controller->update($request, $lab->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('laboratorium.index'), $response->getTargetUrl());
        self::assertDatabaseHas('laboratorium', [
            'id'       => $lab->id,
            'nama_lab' => 'Lab Nama Baru',
            'is_aktif' => 0,
        ]);
    }

    /**
     * Test destroy allows Direktur to delete.
     */
    public function test_destroy_allows_direktur(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $lab = Laboratorium::factory()->create();
        $controller = new LaboratoriumWebController();

        $response = $controller->destroy($lab->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('laboratorium', ['id' => $lab->id]);
    }

    /**
     * Test destroy rejects non-direktur.
     */
    public function test_destroy_rejects_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $lab = Laboratorium::factory()->create();
        $controller = new LaboratoriumWebController();

        $response = $controller->destroy($lab->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('laboratorium', ['id' => $lab->id]);
        self::assertTrue(session()->has('error'));
    }
}
