<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthWebController;
use App\Http\Controllers\Web\DokterWebController;
use App\Http\Controllers\Web\AsistenWebController;
use App\Http\Controllers\Web\LaboratoriumWebController;
use App\Http\Controllers\Web\JenisGigiWebController;
use App\Http\Controllers\Web\PasienWebController;
use App\Http\Controllers\Web\PemeriksaanWebController;
use App\Http\Controllers\Web\PemesananWebController;

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

    // Dashboard (Menggunakan Route::view sebagai pengganti Closure yang efisien)
    Route::view('/dashboard', 'dashboard')->name('dashboard');

    // 📂 DATA MASTER (Otomatis ditambahkan awalan URL: /data-master/...)
    Route::prefix('data-master')->group(function () {

        // Menggunakan Route::resource untuk memborong 6 rute CRUD sekaligus
        // Nama rute otomatis selaras dengan Blade (contoh: dokter.index, dokter.create)
        Route::resource('dokter', DokterWebController::class);
        Route::resource('asisten', AsistenWebController::class);
        Route::resource('laboratorium', LaboratoriumWebController::class);

        // Penyesuaian parameter khusus untuk entitas dengan nama berpenghubung
        Route::resource('jenis-gigi', JenisGigiWebController::class)->parameters([
            'jenis-gigi' => 'id'
        ]);
    });

    // 👥 PASIEN
    Route::resource('pasien', PasienWebController::class);

    // 🏥 PEMERIKSAAN
    Route::resource('pemeriksaan', PemeriksaanWebController::class);

    // 📦 PEMESANAN
    // PENTING: Rute spesifik/custom diletakkan SEBELUM Route::resource
    // agar tidak salah dibaca oleh Laravel sebagai parameter variabel {id}
    Route::get('/riwayat-pemesanan', [PemesananWebController::class, 'riwayat'])->name('pemesanan-riwayat');
    Route::get('/pemesanan/tambah-item', [PemesananWebController::class, 'tambahitem'])->name('pemesanan.tambahitem');

    Route::resource('pemesanan', PemesananWebController::class);

});
