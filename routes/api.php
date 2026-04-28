<?php

use App\Http\Controllers\AsistenController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DokterController;
use App\Http\Controllers\JenisGigiController;
use App\Http\Controllers\LaboratoriumController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\Web\JenisGigiWebController;
use App\Models\JenisGigi;
use Illuminate\Support\Facades\Route;

// RUTE PUBLIK (Tidak perlu tiket/token)
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

// Rute untuk Master Laboratorium
Route::get('/laboratorium', [LaboratoriumController::class, 'index']);       
Route::post('/laboratorium', [LaboratoriumController::class, 'store']);      
Route::put('/laboratorium/{id}', [LaboratoriumController::class, 'update']); 
Route::delete('/laboratorium/{id}', [LaboratoriumController::class, 'destroy']);
Route::get('/laboratorium-semua', [LaboratoriumController::class, 'semua']);
Route::put('/laboratorium-restore/{id}', [LaboratoriumController::class, 'restore']);

// Rute Master Dokter
Route::get('/dokter', [DokterController::class, 'index']);
Route::post('/dokter', [DokterController::class, 'store']);
Route::put('/dokter/{id}', [DokterController::class, 'update']);
Route::delete('/dokter/{id}', [DokterController::class, 'destroy']);
Route::get('/dokter-semua', [DokterController::class, 'semua']);
Route::put('/dokter-restore/{id}', [DokterController::class, 'restore']);

// Rute Master Asisten
Route::get('/asisten', [AsistenController::class, 'index']);
Route::post('/asisten', [AsistenController::class, 'store']);
Route::put('/asisten/{id}', [AsistenController::class, 'update']);
Route::delete('/asisten/{id}', [AsistenController::class, 'destroy']);
Route::get('/asisten-semua', [AsistenController::class, 'semua']);
Route::put('/asisten-restore/{id}', [AsistenController::class, 'restore']);

// Rute Master Jenis Gigi
Route::get('/jenis-gigi', [JenisGigiController::class, 'index']);
Route::post('/jenis-gigi', [JenisGigiController::class, 'store']);
Route::put('/jenis-gigi/{id}', [JenisGigiController::class, 'update']);
Route::delete('/jenis-gigi/{id}', [JenisGigiWebController::class, 'destroy']);
Route::get('/jenis-gigi-semua', [JenisGigiController::class, 'semua']);
Route::put('/jenis-gigi-restore/{id}', [JenisGigiController::class, 'restore']);

// Routes Pasien
Route::get('/pasien', [PasienController::class, 'index']);
Route::post('/pasien', [PasienController::class, 'store']);
Route::put('/pasien/{id}', [PasienController::class, 'update']);
Route::delete('/pasien/{id}', [PasienController::class, 'destroy']);
Route::get('/pasien-semua', [PasienController::class, 'semua']);
Route::put('/pasien-restore/{id}', [PasienController::class, 'restore']);
});
