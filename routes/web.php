<?php

use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

// Rute Pancingan (Nanti kita ganti dengan Controller beneran)
Route::get('/login', function () {
    return "Halaman Form Login Web akan segera dibuat di sini!";
})->name('login'); // -> name('login') ini yang dicari-cari oleh Laravel tadi!
