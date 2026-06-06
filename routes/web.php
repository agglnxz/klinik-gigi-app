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
Route::get('/', [AuthWebController::class, 'showLoginForm'])->name('auth.login');
Route::get('/login', [AuthWebController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthWebController::class, 'login']);


// -----------------------------------------------------------------------------
// RUTE TERLINDUNGI (Wajib Login)
// -----------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // 🟢 AKSES UMUM (Semua Akun yang Sudah Login Bisa Akses)
    Route::post('/logout', [AuthWebController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardWebController::class, 'dashboard'])->name('dashboard');
    Route::get('/notifikasi', [NotifikasiWebController::class, 'index'])->name('notifikasi.index');


    // 🟡 KELOMPOK HAK AKSES: KHUSUS ADMIN STAFF
    Route::middleware('role:Admin')->group(function () {

        // Data Master
        Route::prefix('data-master')->group(function () {
            Route::resource('dokter', DokterWebController::class)->except('show');
            Route::resource('asisten', AsistenWebController::class)->except('show');
            Route::resource('laboratorium', LaboratoriumWebController::class)->except('show');
            Route::resource('jenis-gigi', JenisGigiWebController::class)->except('show');
        });

        // Pasien & Pemeriksaan Medis (Marketing Dilarang Masuk demi Privasi Rekam Medis)
        Route::resource('pasien', PasienWebController::class)->except('show');
        Route::resource('pemeriksaan', PemeriksaanWebController::class)->except('show');
    });

        // 🔵 KELOMPOK HAK AKSES: ADMIN, MARKETING, & DIREKTUR
    Route::middleware('role:Admin,Marketing,Direktur')->group(function () {
        Route::get('pemesanan', [PemesananWebController::class, 'index'])->name('pemesanan.index');
        Route::get('pemesanan/{pemesanan}', [PemesananWebController::class, 'show'])->name('pemesanan.show');
        Route::get('riwayat-pemesanan', [PemesananWebController::class, 'pemesananRiwayat'])->name('pemesanan-riwayat');
    });

    // 🟡 PEMESANAN - WRITE: Hanya Admin yang Bisa Tambah & Edit Data
    Route::middleware('role:Admin')->group(function () {
        Route::get('pemesanan/create', [PemesananWebController::class, 'create'])->name('pemesanan.create');
        Route::post('pemesanan', [PemesananWebController::class, 'store'])->name('pemesanan.store');
        Route::get('pemesanan/{pemesanan}/edit', [PemesananWebController::class, 'edit'])->name('pemesanan.edit');
        Route::put('pemesanan/{pemesanan}', [PemesananWebController::class, 'update'])->name('pemesanan.update');
        Route::post('/pengajuan-hapus', [PengajuanHapusWebController::class, 'store'])->name('pengajuan-hapus.store');
    });


    // 🔴 KELOMPOK HAK AKSES: MUTLAK DIREKTUR UTAMA SAJA
    Route::middleware('role:Direktur')->group(function () {
        // Approval Pengajuan Hapus Data
        Route::get('/pengajuan-hapus', [PengajuanHapusWebController::class, 'index'])->name('pengajuan-hapus.index');
        Route::post('/pengajuan-hapus/{id}/approve', [PengajuanHapusWebController::class, 'approve'])->name('pengajuan-hapus.approve');
        Route::post('/pengajuan-hapus/{id}/reject', [PengajuanHapusWebController::class, 'reject'])->name('pengajuan-hapus.reject');
        // Manajemen Akun Karyawan/Staf Klinik
        Route::get('/users', [UserWebController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserWebController::class, 'create'])->name('users.create');
    });

});
