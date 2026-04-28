<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::prefix('data-master')->group(function () {

    // Route untuk Dokter
    Route::get('/dokter', function () {
        return view('data_master.dokter.index');
    })->name('dokter.index');

    // Route untuk Asisten
    Route::get('/asisten', function () {
        return view('data_master.asisten.index');
    })->name('asisten.index');

    // Nanti Anda bisa tambah di sini untuk Laboratorium, Jenis Gigi, dll
    // Route::get('/laboratorium', ...);
});
