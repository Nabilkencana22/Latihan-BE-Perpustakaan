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

    // Buku (Akses diatur di dalam BukuController)
    Route::apiResource('buku', BukuController::class);

    // Kategori (Read untuk semua user terautentikasi)
    Route::get('kategori', [KategoriController::class, 'index']);
    Route::get('kategori/{id}', [KategoriController::class, 'show']);

    // Akses untuk Petugas dan Admin
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('anggota', [AnggotaController::class, 'index']);
        Route::get('anggota/{id}', [AnggotaController::class, 'show']);

        Route::get('peminjaman', [PeminjamanController::class, 'index']);
        Route::post('peminjaman', [PeminjamanController::class, 'store']);
        Route::get('peminjaman/{id}', [PeminjamanController::class, 'show']);

        Route::apiResource('pengembalian', PengembalianController::class);
    });

    // Akses khusus Admin
    Route::middleware('role:admin')->group(function () {
        Route::patch('/user/{id}/role', [AuthController::class, 'updateRole']);
        Route::apiResource('kategori', KategoriController::class)->except(['index', 'show']);
        Route::apiResource('anggota', AnggotaController::class)->except(['index', 'show']);
        Route::delete('peminjaman/{id}', [PeminjamanController::class, 'destroy']);
    });
});
