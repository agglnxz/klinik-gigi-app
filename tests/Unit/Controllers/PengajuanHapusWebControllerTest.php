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

        // Act berfungsi untuk mengeksekusi
        // untuk kasus ini menjalankan method index()
        $response = $controller->index();

        // Assert berfungsi untuk memverifikasi hasil apakah sudah sesuai dengan yang diharapkan
        self::assertInstanceOf(View::class, $response); // memastikan method index mengembalikan view
        self::assertSame('pengajuan_hapus.index', $response->name()); // memastikan view yang di render sesuai dengan controller

        $data = $response->getData(); // mengambil seluruh data yang dikirim ke view

        // Memastikan controller menghitung jumlah data per status approval dengan tepat
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

    /**
     * Data provider untuk role non-direktur.
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
     * Test approve is forbidden for non-direktur roles (Admin and Marketing).
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('nonDirekturRoles')]
    public function test_approve_forbids_non_direktur_roles(string $role): void
    {
        // Arrange
        $user = User::factory()->create(['role' => $role]);
        Auth::login($user);

        $pasien = Pasien::factory()->create();
        $pengajuan = PengajuanHapus::factory()->create([
            'nama_tabel'      => 'pasien',
            'id_referensi'    => $pasien->id,
            'status_approval' => 'Pending',
        ]);

        $controller = new PengajuanHapusWebController();

        // Act
        $response = $controller->approve($pengajuan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame('Akses Terbatas.', session('error'));

        // Status must remain Pending and patient must NOT be deleted
        $pengajuan->refresh();
        self::assertSame('Pending', $pengajuan->status_approval);
        self::assertDatabaseHas('pasien', ['id' => $pasien->id]);
    }

    /**
     * Test reject is forbidden for non-direktur roles (Admin and Marketing).
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('nonDirekturRoles')]
    public function test_reject_forbids_non_direktur_roles(string $role): void
    {
        // Arrange
        $user = User::factory()->create(['role' => $role]);
        Auth::login($user);

        $pengajuan = PengajuanHapus::factory()->create([
            'status_approval' => 'Pending',
        ]);

        $controller = new PengajuanHapusWebController();

        
        $response = $controller->reject($pengajuan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame('Akses Terbatas.', session('error'));

        $pengajuan->refresh();
        self::assertSame('Pending', $pengajuan->status_approval);
    }

    /**
     * Test store prevents duplicate pending deletion request for the same record.
     */
    public function test_store_prevents_duplicate_pending_deletion_request(): void
    {
        // Arrange
        $user = User::factory()->create();
        Auth::login($user);

        $pasien = Pasien::factory()->create();

        // First deletion request
        PengajuanHapus::create([
            'nama_tabel'      => 'pasien',
            'id_referensi'    => (string) $pasien->id,
            'nama_data'       => $pasien->nama,
            'alasan_hapus'    => 'Pengajuan pertama valid',
            'status_approval' => 'Pending',
            'id_pemohon'      => $user->id,
        ]);

        $controller = new PengajuanHapusWebController();

        $secondRequest = Request::create('/pengajuan-hapus', 'POST', [
            'nama_tabel'   => 'pasien',
            'id_referensi' => $pasien->id,
            'nama_data'    => $pasien->nama,
            'alasan_hapus' => 'Pengajuan kedua tidak boleh dobel',
        ]);
        $secondRequest->setLaravelSession(app('session.store'));

        // Act
        $response = $controller->store($secondRequest);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertStringContainsString('menunggu persetujuan', session('error'));

        // Count should remain 1
        self::assertSame(1, PengajuanHapus::where('nama_tabel', 'pasien')->where('id_referensi', $pasien->id)->count());
    }

    /**
     * Test store fails and does not save when alasan_hapus is too short (< 5 characters).
     */
    public function test_store_fails_when_reason_is_too_short(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $pasien = Pasien::factory()->create();

        $request = Request::create('/pengajuan-hapus', 'POST', [
            'nama_tabel'   => 'pasien',
            'id_referensi' => $pasien->id,
            'nama_data'    => $pasien->nama,
            'alasan_hapus' => '1234', // 4 chars (min:5)
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PengajuanHapusWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for short alasan_hapus');
        } catch (\Illuminate\Validation\ValidationException $e) {
            self::assertArrayHasKey('alasan_hapus', $e->errors());
        }

        self::assertDatabaseCount('pengajuan_hapus', 0);
    }

    /**
     * Test store fails and does not save when nama_tabel is not in allowed whitelist.
     */
    public function test_store_fails_when_target_table_is_invalid(): void
    {
        $user = User::factory()->create();
        Auth::login($user);

        $request = Request::create('/pengajuan-hapus', 'POST', [
            'nama_tabel'   => 'users', // table not in allowed list
            'id_referensi' => 1,
            'nama_data'    => 'Administrator',
            'alasan_hapus' => 'Mencoba menghapus user lewat pengajuan',
        ]);
        $request->setLaravelSession(app('session.store'));

        $controller = new PengajuanHapusWebController();

        try {
            $controller->store($request);
            self::fail('Expected ValidationException for unwhitelisted nama_tabel');
        } catch (\Illuminate\Validation\ValidationException $e) {
            self::assertArrayHasKey('nama_tabel', $e->errors());
        }

        self::assertDatabaseCount('pengajuan_hapus', 0);
    }

    /**
     * Test approve returns error when request has already been processed.
     */
    public function test_approve_fails_when_already_processed(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pengajuan = PengajuanHapus::factory()->create([
            'status_approval' => 'Disetujui',
        ]);

        $controller = new PengajuanHapusWebController();
        $response = $controller->approve($pengajuan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame('Sudah diproses.', session('error'));
    }

    /**
     * Test reject returns error when request has already been processed.
     */
    public function test_reject_fails_when_already_processed(): void
    {
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        $pengajuan = PengajuanHapus::factory()->create([
            'status_approval' => 'Ditolak',
        ]);

        $controller = new PengajuanHapusWebController();
        $response = $controller->reject($pengajuan->id);

        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame('Sudah diproses.', session('error'));
    }

    /**
     * Test approve maintains consistency and keeps pending status if deletion fails due to relation constraints.
     */
    public function test_approve_handles_foreign_key_constraint_gracefully_and_keeps_pending_status(): void
    {
        // Arrange
        $direktur = User::factory()->create(['role' => 'Direktur']);
        Auth::login($direktur);

        // Buat jenis gigi yang direferensikan oleh pemesanan_items (Foreign Key tanpa CASCADE)
        $jenisGigi = \App\Models\JenisGigi::factory()->create();
        $pemesanan = \App\Models\Pemesanan::factory()->create();
        \App\Models\PemesananItem::create([
            'id_pemesanan'  => $pemesanan->id,
            'id_jenis_gigi' => $jenisGigi->id,
        ]);

        $pengajuan = PengajuanHapus::factory()->create([
            'nama_tabel'      => 'jenis_gigi',
            'id_referensi'    => $jenisGigi->id,
            'status_approval' => 'Pending',
        ]);

        $controller = new PengajuanHapusWebController();

        // Act
        $response = $controller->approve($pengajuan->id);

        // Assert
        self::assertInstanceOf(RedirectResponse::class, $response);
        self::assertTrue(session()->has('error'));
        self::assertSame('Gagal mengeksekusi penghapusan. Data terikat relasi.', session('error'));

        // Status pengajuan tetap konsisten 'Pending'
        $pengajuan->refresh();
        self::assertSame('Pending', $pengajuan->status_approval);

        // Data jenis gigi tetap ada di database (tidak terhapus)
        self::assertDatabaseHas('jenis_gigi', [
            'id' => $jenisGigi->id,
        ]);
    }
}
