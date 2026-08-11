<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::patch('/user/{id}/role', [AuthController::class, 'updateRole']);

    Route::apiResource('buku', BukuController::class);
    Route::apiResource('kategori' , KategoriController::class);
    Route::apiResource('anggota' , AnggotaController::class);
    Route::apiResource('peminjaman' , PeminjamanController::class);
    Route::apiResource('pengembalian' , PengembalianController::class);
});