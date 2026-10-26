<?php

namespace Tests\Unit\Controllers;

use App\Http\Controllers\Web\PengajuanHapusWebController;
use App\Models\Pasien;
use App\Models\PengajuanHapus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Tests\TestCase;

class PengajuanHapusWebControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test index allows Direktur to view deletion requests and statistics.
     */
    public function test_index_allows_direktur(): void
    {
        // Arrange
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        PengajuanHapus::factory()->create(['status_approval' => 'Pending']);
        PengajuanHapus::factory()->create(['status_approval' => 'Disetujui']);
        PengajuanHapus::factory()->create(['status_approval' => 'Ditolak']);

        $controller = new PengajuanHapusWebController();

        // Act
        $response = $controller->index();

        // Assert
        self::assertInstanceOf(View::class, $response);
        self::assertSame('pengajuan_hapus.index', $response->name());

        $data = $response->getData();
        self::assertSame(1, $data['menunggu']);
        self::assertSame(1, $data['disetujui']);
        self::assertSame(1, $data['ditolak']);
    }

    /**
     * Test index redirects non-direktur users to dashboard with error.
     */
    public function test_index_forbids_non_direktur(): void
    {
        $admin = User::factory()->create(['role' => 'Admin']);
        Auth::login($admin);

        $controller = new PengajuanHapusWebController();
        $response = $controller->index();

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertSame(route('dashboard'), $response->getTargetUrl());
        self::assertTrue(session()->has('error'));
    }

    /**
     * Test store creates new PengajuanHapus when data is valid and exists in table.
     */
    public function test_store_creates_pengajuan_hapus(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $pasien = Pasien::factory()->create(['nama' => 'Pasien Mau Dihapus']);

        $request = Request::create('/pengajuan-hapus', 'POST', [
            'nama_tabel'   => 'pasien',
            'id_referensi' => $pasien->id,
            'nama_data'    => $pasien->nama,
            'alasan_hapus' => 'Data pasien ganda/duplikat',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PengajuanHapusWebController();

        // Act
        $response = $controller->store($request);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertDatabaseHas('pengajuan_hapus', [
            'nama_tabel'      => 'pasien',
            'id_referensi'    => (string) $pasien->id,
            'status_approval' => 'Pending',
            'id_pemohon'      => $user->id,
        ]);
    }

    /**
     * Test store detects invalid ID (non-existent record in target table).
     */
    public function test_store_detects_non_existent_data(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $request = Request::create('/pengajuan-hapus', 'POST', [
            'nama_tabel'   => 'pasien',
            'id_referensi' => 999999, // non-existent
            'nama_data'    => 'Data Fiktif',
            'alasan_hapus' => 'Alasan coba hapus',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PengajuanHapusWebController();
        $response = $controller->store($request);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertDatabaseMissing('pengajuan_hapus', [
            'nama_tabel'   => 'pasien',
            'id_referensi' => '999999',
        ]);
    }

    /**
     * Test approve executes table deletion and updates approval status to 'Disetujui'.
     */
    public function test_approve_deletes_record_and_approves(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pasien = Pasien::factory()->create();
        $pengajuan = PengajuanHapus::factory()->create([
            'nama_tabel'      => 'pasien',
            'id_referensi'    => $pasien->id,
            'status_approval' => 'Pending',
        ]);

        $controller = new PengajuanHapusWebController();
        $response = $controller->approve($pengajuan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        $pengajuan->refresh();
        self::assertSame('Disetujui', $pengajuan->status_approval);
        self::assertDatabaseMissing('pasien', ['id' => $pasien->id]);
    }

    /**
     * Test reject updates approval status to 'Ditolak'.
     */
    public function test_reject_updates_status_to_ditolak(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pengajuan = PengajuanHapus::factory()->create([
            'status_approval' => 'Pending',
        ]);

        $controller = new PengajuanHapusWebController();
        $response = $controller->reject($pengajuan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        $pengajuan->refresh();
        self::assertSame('Ditolak', $pengajuan->status_approval);
    }
}
