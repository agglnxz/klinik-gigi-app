<?php

use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JenisGigiController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PasienController;
use Illuminate\Support\Facades\Route;

// RUTE PUBLIK (Tidak perlu tiket/token)
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

// Rute untuk Master Laboratorium
Route::get('/laboratorium', [LaboratoriumController::class, 'index']);       // Lihat semua
Route::post('/laboratorium', [LaboratoriumController::class, 'store']);      // Tambah baru
Route::put('/laboratorium/{id}', [LaboratoriumController::class, 'update']); // Ubah data

// Rute Master Dokter
Route::get('/dokter', [DokterController::class, 'index']);
Route::post('/dokter', [DokterController::class, 'store']);
Route::put('/dokter/{id}', [DokterController::class, 'update']);

// Rute Master Asisten
Route::get('/asisten', [AsistenController::class, 'index']);
Route::post('/asisten', [AsistenController::class, 'store']);
Route::put('/asisten/{id}', [AsistenController::class, 'update']);

// Rute Master Jenis Gigi
Route::get('/jenis-gigi', [JenisGigiController::class, 'index']);
Route::post('/jenis-gigi', [JenisGigiController::class, 'store']);
Route::put('/jenis-gigi/{id}', [JenisGigiController::class, 'update']);

// Routes Pasien
Route::get('/pasien', [PasienController::class, 'index']);
Route::post('/pasien', [PasienController::class, 'store']);
Route::put('/pasien/{id}', [PasienController::class, 'update']);
Route::delete('/pasien/{id}', [PasienController::class, 'destroy']);
Route::get('/pasien-semua', [PasienController::class, 'semua']);
Route::put('/pasien-restore/{id}', [PasienController::class, 'restore']);
});
