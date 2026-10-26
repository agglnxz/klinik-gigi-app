<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\PasienWebController;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class PasienWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index displays list of pasien and handles search.
     */
    public function test_index_displays_view_and_handles_search(): void
    {
        // Arrange
        $p1 = Pasien::factory()->create(['nama' => 'Joko Widodo', 'no_rm' => 'RM-101']);
        $p2 = Pasien::factory()->create(['nama' => 'Susilo Bambang', 'no_rm' => 'RM-102']);

        $request = Request::create('/pasien', 'GET', ['search' => 'Joko']);
        $controller = new PasienWebController();

        // Act
        $response = $controller->index($request);

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('pasien.index', $response->name());

        $data = $response->getData();
        self::assertArrayHasKey('pasien', $data);
        self::assertTrue($data['pasien']->contains('id', $p1->id));
        self::assertFalse($data['pasien']->contains('id', $p2->id));
    }

    /**
     * Test create returns pasien.create view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new PasienWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('pasien.create', $response->name());
    }

    /**
     * Test store saves new patient and redirects.
     */
    public function test_store_creates_patient(): void
    {
        // Arrange
        $request = Request::create('/pasien', 'POST', [
            'no_rm'         => 'RM-2026-9999',
            'nama'          => 'Megawati Soekarnoputri',
            'kontak'        => '081234567890',
            'jenis_kelamin' => 'Perempuan',
            'alamat'        => 'Jl. Teuku Umar No. 1',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PasienWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pasien.index'), $response->getTargetUrl());
        self::assertDatabaseHas('pasien', [
            'no_rm' => 'RM-2026-9999',
            'nama'  => 'Megawati Soekarnoputri',
        ]);
    }

    /**
     * Test edit returns pasien.update view with patient data.
     */
    public function test_edit_returns_update_view(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create();
        $controller = new PasienWebController();

        // Act
        $response = $controller->edit($pasien->id);

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('pasien.update', $response->name());
        self::assertTrue($response->getData()['pasien']->is($pasien));
    }

    /**
     * Test update modifies existing patient.
     */
    public function test_update_modifies_patient(): void
    {
        // Arrange
        $pasien = Pasien::factory()->create([
            'nama'   => 'Nama Lama',
            'kontak' => '0811111111',
        ]);

        $request = Request::create("/pasien/{$pasien->id}", 'PUT', [
            'no_rm'         => $pasien->no_rm,
            'nama'          => 'Nama Baru Diperbarui',
            'kontak'        => '0822222222',
            'jenis_kelamin' => 'Laki-laki',
            'alamat'        => 'Alamat Baru',
            'is_aktif'      => 1,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PasienWebController();

        // Act
        $response = $controller->update($request, $pasien->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('pasien.index'), $response->getTargetUrl());
        self::assertDatabaseHas('pasien', [
            'id'   => $pasien->id,
            'nama' => 'Nama Baru Diperbarui',
        ]);
    }

    /**
     * Test destroy allows Direktur to delete patient.
     */
    public function test_destroy_allows_direktur_to_soft_delete(): void
    {
        // Arrange
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pasien = Pasien::factory()->create();
        $controller = new PasienWebController();

        // Act
        $response = $controller->destroy($pasien->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSoftDeleted('pasien', ['id' => $pasien->id]);
    }

    /**
     * Test destroy forbids non-direktur roles.
     */
    public function test_destroy_forbids_non_direktur_user(): void
    {
        // Arrange
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $pasien = Pasien::factory()->create();
        $controller = new PasienWebController();

        // Act
        $response = $controller->destroy($pasien->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertNotSoftDeleted('pasien', ['id' => $pasien->id]);
        self::assertTrue(session()->has('error'));
    }
}
