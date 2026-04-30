<?php

use Illuminate\Support\Facades\Route;

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

    Route::get('/asisten', function () {
        return view('data_master.asisten.index');
    })->name('asisten.index');

    // Route untuk Laboratorium
    Route::get('/laboratorium', function () {
        return view('data_master.laboratorium.index');
    })->name('laboratorium.index');

    // Route untuk Jenis Gigi
    Route::get('/jenis-gigi', function () {
        return view('data_master.jenis_gigi.index');
    })->name('jenis-gigi.index');

});

Route::get('/pasien', function () {
    return view('pasien.index');
})->name('pasien.index');

Route::get('/pemeriksaan', function () {
    return view('pemeriksaan.index');
})->name('pemeriksaan.index');

Route::get('/pemesanan', function () {
    return view('pemesanan.index');
})->name('pemesanan.index');

Route::get('/riwayat-pemesanan', function () {
    return view('riwayat_pemesanan.index');
})->name('pemesanan-riwayat');
