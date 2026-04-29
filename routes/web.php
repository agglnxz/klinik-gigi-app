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

});

Route::get('/pemeriksaan', function () {
    return view('pemeriksaan.index');
})->name('pemeriksaan.index');

Route::get('/pemesanan', function () {
    return view('pemesanan.index');
})->name('pemesanan');
