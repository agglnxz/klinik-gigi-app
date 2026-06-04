<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\NotifikasiWebController;
use App\Http\Controllers\Web\DashboardWebController;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DokterWebController;
use App\Http\Controllers\Web\AsistenWebController;
use App\Http\Controllers\Web\LaboratoriumWebController;
use App\Http\Controllers\Web\JenisGigiWebController;
use App\Http\Controllers\Web\PasienWebController;
use App\Http\Controllers\Web\PemeriksaanWebController;
use App\Http\Controllers\Web\PemesananWebController;
use App\Http\Controllers\Web\PengajuanHapusWebController;
use App\Http\Controllers\Web\UserWebController;


// -----------------------------------------------------------------------------
// RUTE PUBLIK (Aman Diakses Tanpa Login)
// -----------------------------------------------------------------------------

// Mengarahkan root dan /login langsung ke Controller agar mendukung Route Caching
Route::get('/', [AuthWebController::class, 'showLoginForm'])->name('auth.login');
Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);


// -----------------------------------------------------------------------------
// RUTE TERLINDUNGI (Wajib Login / Akses ditolak jika tidak ada sesi aktif)
// -----------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // Jalur Keluar (Wajib POST demi keamanan CSRF)
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');

    // 📊 DASHBOARD
    Route::get('/dashboard', [DashboardWebController::class, 'dashboard'])->name('dashboard');

    // 📂 DATA MASTER (Otomatis ditambahkan awalan URL: /data-master/...)
    Route::prefix('data-master')->group(function () {

        // Menggunakan Route::resource untuk memborong 6 rute CRUD sekaligus
        // Nama rute otomatis selaras dengan Blade (contoh: dokter.index, dokter.create)
        Route::resource('dokter', DokterWebController::class)->except('show');
        Route::resource('asisten', AsistenWebController::class)->except('show');
        Route::resource('laboratorium', LaboratoriumWebController::class)->except('show');
        Route::resource('jenis-gigi', JenisGigiWebController::class)->except('show');
    });

    // 👥 PASIEN
    Route::resource('pasien', PasienWebController::class)->except('show');

    // 🏥 PEMERIKSAAN
    Route::resource('pemeriksaan', PemeriksaanWebController::class)->except('show');

    // 📅 PEMESANAN
    Route::resource('pemesanan', PemesananWebController::class);

    // 📜 RIWAYAT PEMESANAN
    Route::get('riwayat-pemesanan', [PemesananWebController::class, 'pemesananRiwayat'])->name('pemesanan-riwayat');

    //Notifikasi
    Route::get('/notifikasi', [NotifikasiWebController::class, 'index'])->name('notifikasi.index');

    // 📝 APPROVAL
    Route::get('/pengajuan-hapus', [PengajuanHapusWebController::class, 'index'])->name('pengajuan-hapus.index');

    // 👤 USER MANAGEMENT (Hanya untuk Admin, bisa ditambahkan middleware khusus jika diperlukan)
    // Route::resource('users', UserWebController::class);
    Route::get('/users', [UserWebController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserWebController::class, 'create'])->name('users.create');
});
