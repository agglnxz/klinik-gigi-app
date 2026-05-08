<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\PemesananWebController;
use App\Http\Controllers\Web\PemeriksaanWebController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


// ✅ DATA MASTER (tetap seperti kamu)
Route::prefix('data-master')->group(function () {

    Route::get('/dokter', function () {
        return view('data_master.dokter.index');
    })->name('dokter.index');
    Route::get('/data-master/dokter/tambah', function () {
        return view('data_master.dokter.create');
    })->name('dokter.create');
    Route::get('/data-master/dokter/{id}/edit', function ($id) {
        return view('data_master.dokter.update');
    })->name('dokter.edit');

    Route::get('/asisten', function () {
        return view('data_master.asisten.index');
    })->name('asisten.index');
    Route::get('/data-master/asisten/tambah', function () {
        return view('data_master.asisten.create');
    })->name('asisten.create');
    Route::get('/data-master/asisten/{id}/edit', function ($id) {
        return view('data_master.asisten.update');
    })->name('asisten.edit');

    // Route untuk Laboratorium
    Route::get('/laboratorium', function () {
        return view('data_master.laboratorium.index');
    })->name('laboratorium.index');
    Route::get('/data-master/laboratorium/tambah', function () {
        return view('data_master.laboratorium.create');
    })->name('laboratorium.create');
    Route::get('/data-master/laboratorium/{id}/edit', function ($id) {
        return view('data_master.laboratorium.update');
    })->name('laboratorium.edit');

    // Route untuk Jenis Gigi
    Route::get('/jenis-gigi', function () {
        return view('data_master.jenis_gigi.index');
    })->name('jenis-gigi.index');
     Route::get('/data-master/jenis-gigi/tambah', function () {
        return view('data_master.jenis_gigi.create');
    })->name('jenis-gigi.create');
    Route::get('/data-master/jenis-gigi/{id}/edit', function ($id) {
        return view('data_master.jenis_gigi.update');
    })->name('jenis-gigi.edit');

});

Route::get('/pasien', function () {
    return view('pasien.index');
})->name('pasien.index');
Route::get('/pasien/tambah', function () {
    return view('pasien.create');
})->name('pasien.create');
Route::get('/pasien/{id}/edit', function ($id) {
    return view('pasien.update');
})->name('pasien.edit');

Route::get('/pemeriksaan', [PemeriksaanWebController::class, 'index'])
    ->name('pemeriksaan.index');

Route::get('/pemeriksaan/tambah', [PemeriksaanWebController::class, 'create'])
    ->name('pemeriksaan.create');

Route::post('/pemeriksaan', [PemeriksaanWebController::class, 'store'])
    ->name('pemeriksaan.store');

Route::get('/pemeriksaan/{id}/edit', [PemeriksaanWebController::class, 'edit'])
    ->name('pemeriksaan.edit');

Route::put('/pemeriksaan/{id}', [PemeriksaanWebController::class, 'update'])
    ->name('pemeriksaan.update');
// Route::get('/pemeriksaan', [PemeriksaanWebController::class, 'index'])
//     ->name('pemeriksaan.index');

// Route::get('/pemeriksaan/tambah', [PemeriksaanWebController::class, 'create'])
//     ->name('pemeriksaan.create');

// Route::get('/pemeriksaan/{id}/edit', [PemeriksaanWebController::class, 'edit'])
//     ->name('pemeriksaan.edit');
//     Route::put('/pemeriksaan/{id}', [PemeriksaanWebController::class, 'update'])
//     ->name('pemeriksaan.update');
// Route::get('/pemeriksaan', function () {
//     return view('pemeriksaan.index');
// })->name('pemeriksaan.index');
// Route::get('/pemeriksaan/tambah', function () {
//     return view('Pemeriksaan.create');
// })->name('pemeriksaan.create');
// Route::get('/pemeriksaan/{id}/edit', function ($id) {
//     return view('pemeriksaan.create');
// })->name('pemeriksaan.edit');

// Route::get('/pemesanan', function () {
//     return view('pemesanan.index');
// })->name('pemesanan.index');
// Route::get('/pemesanan/tambah', function () {
//     return view('pemesanan.create');
// })->name('pemesanan.create');

// Route::get('/pemesanan/tambah-item', function () {
//     return view('pemesanan.tambahitem');
// })->name('pemesanan.tambahitem');

//     Route::get('/pemesanan/{id}/edit', function ($id) {
//         return view('pemesanan.update');
//     })->name('pemesanan.edit');
// // Route::get('/pemesanan/{id}/edit', [PemesananWebController::class, 'edit'])->name('pemesanan.edit');

// Route::get('/riwayat-pemesanan', function () {
//     return view('riwayat_pemesanan.index');
// })->name('pemesanan-riwayat');

Route::get('/pemesanan', [PemesananWebController::class, 'index'])
    ->name('pemesanan.index');

Route::get('/pemesanan/tambah', [PemesananWebController::class, 'create'])
    ->name('pemesanan.create');

Route::post('/pemesanan', [PemesananWebController::class, 'store'])
    ->name('pemesanan.store');

Route::get('/pemesanan/tambah-item', [PemesananWebController::class, 'tambahitem'])
    ->name('pemesanan.tambahitem');

Route::get('/pemesanan/{id}/edit', [PemesananWebController::class, 'edit'])->name('pemesanan.edit');

Route::put('/pemesanan/{id}', [PemesananWebController::class, 'update'])
    ->name('pemesanan.update');

Route::delete('/pemesanan/{id}', [PemesananWebController::class, 'destroy'])
    ->name('pemesanan.destroy');

Route::get('/riwayat-pemesanan', [PemesananWebController::class, 'riwayat'])
    ->name('pemesanan-riwayat');
