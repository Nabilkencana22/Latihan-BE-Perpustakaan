<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('buku', BukuController::class);

    Route::get('kategori', [KategoriController::class, 'index']);
    Route::get('kategori/{id}', [KategoriController::class, 'show']);

    Route::get('anggota', [AnggotaController::class, 'index']);
    Route::get('anggota/{id}', [AnggotaController::class, 'show']);

    Route::middleware('role:admin')->group(function () {
        Route::patch('/user/{id}/role', [AuthController::class, 'updateRole']);
        Route::apiResource('kategori', KategoriController::class);
        Route::apiResource('anggota', AnggotaController::class);
        Route::apiResource('peminjaman', PeminjamanController::class);
        Route::apiResource('pengembalian', PengembalianController::class);
    });

    Route::middleware('role:petugas')->group(function () {
        Route::get('anggota', [AnggotaController::class, 'index']); 
        Route::get('anggota/{id}', [AnggotaController::class, 'show']);
        Route::apiResource('peminjaman', PeminjamanController::class)->except(['destroy']);
        Route::apiResource('pengembalian', PengembalianController::class);
    });
});
