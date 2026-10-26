<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\DokterWebController;
use App\Models\Dokter;
use App\Models\PengajuanHapus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class DokterWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index returns view with dokter list and pendingHapus IDs.
     */
    public function test_index_returns_view_with_data(): void
    {
        // Arrange
        $dokter = Dokter::factory()->create();
        PengajuanHapus::factory()->create([
            'nama_tabel'   => 'dokter',
            'id_referensi' => $dokter->id,
            'status_approval' => 'Pending',
        ]);

        $controller = new DokterWebController();

        // Act
        $response = $controller->index();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.dokter.index', $response->name());

        $data = $response->getData();
        self::assertTrue($data['dokter']->contains('id', $dokter->id));
        self::assertTrue(in_array((string) $dokter->id, array_map('strval', $data['pendingHapus']), true));
    }

    /**
     * Test create returns create view.
     */
    public function test_create_returns_view(): void
    {
        $controller = new DokterWebController();
        $response = $controller->create();

        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.dokter.create', $response->name());
    }

    /**
     * Test store validates and creates new dokter.
     */
    public function test_store_creates_dokter(): void
    {
        // Arrange
        $request = Request::create('/dokter', 'POST', [
            'nama'   => 'drg. Anindita Putri',
            'kontak' => '081299887766',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new DokterWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('dokter.index'), $response->getTargetUrl());
        self::assertDatabaseHas('dokter', [
            'nama'     => 'drg. Anindita Putri',
            'kontak'   => '081299887766',
            'is_aktif' => 1,
        ]);
    }

    /**
     * Test edit returns update view.
     */
    public function test_edit_returns_update_view(): void
    {
        // Arrange
        $dokter = Dokter::factory()->create();
        $controller = new DokterWebController();

        // Act
        $response = $controller->edit($dokter->id);

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('data_master.dokter.update', $response->name());
        self::assertTrue($response->getData()['dokter']->is($dokter));
    }

    /**
     * Test update modifies existing dokter record.
     */
    public function test_update_modifies_dokter(): void
    {
        // Arrange
        $dokter = Dokter::factory()->create(['nama' => 'drg. Budi Lama']);
        $request = Request::create("/dokter/{$dokter->id}", 'PUT', [
            'nama'     => 'drg. Budi Baru',
            'kontak'   => '0855555555',
            'is_aktif' => 0,
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new DokterWebController();

        // Act
        $response = $controller->update($request, $dokter->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('dokter.index'), $response->getTargetUrl());
        self::assertDatabaseHas('dokter', [
            'id'       => $dokter->id,
            'nama'     => 'drg. Budi Baru',
            'is_aktif' => 0,
        ]);
    }

    /**
     * Test destroy creates PengajuanHapus request when none exists.
     */
    public function test_destroy_submits_pengajuan_hapus(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $dokter = Dokter::factory()->create(['nama' => 'drg. Chandra']);
        $request = Request::create("/dokter/{$dokter->id}", 'DELETE', [
            'alasan_hapus' => 'Dokter pindah tugas dinas',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new DokterWebController();

        // Act
        $response = $controller->destroy($request, $dokter->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertDatabaseHas('pengajuan_hapus', [
            'nama_tabel'      => 'dokter',
            'id_referensi'    => $dokter->id,
            'nama_data'       => 'drg. Chandra',
            'alasan_hapus'    => 'Dokter pindah tugas dinas',
            'status_approval' => 'Pending',
            'id_pemohon'      => $user->id,
        ]);
    }

    /**
     * Test destroy rejects deletion request if another request is already Pending.
     */
    public function test_destroy_prevents_duplicate_pending_request(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $dokter = Dokter::factory()->create();
        PengajuanHapus::create([
            'nama_tabel'      => 'dokter',
            'id_referensi'    => $dokter->id,
            'nama_data'       => $dokter->nama,
            'alasan_hapus'    => 'Pengajuan pertama',
            'status_approval' => 'Pending',
            'id_pemohon'      => $user->id,
        ]);

        $request = Request::create("/dokter/{$dokter->id}", 'DELETE', [
            'alasan_hapus' => 'Pengajuan kedua',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new DokterWebController();

        // Act
        $response = $controller->destroy($request, $dokter->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame(1, PengajuanHapus::where('nama_tabel', 'dokter')->where('id_referensi', $dokter->id)->count());
    }
}
