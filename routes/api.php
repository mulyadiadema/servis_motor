<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ServiceMotorApiController;

// GET: Menampilkan seluruh data
Route::get('/{resource}', [ServiceMotorApiController::class, 'index'])
    ->whereIn('resource', [
        'pelanggan',
        'kendaraan',
        'daftar-service',
        'data-service',
        'pembayaran-service'
    ]);

// POST: Menambahkan data
Route::post('/{resource}', [ServiceMotorApiController::class, 'store'])
    ->whereIn('resource', [
        'pelanggan',
        'kendaraan',
        'daftar-service',
        'data-service',
        'pembayaran-service'
    ]);

// GET: Menampilkan detail berdasarkan ID
Route::get('/{resource}/{id}', [ServiceMotorApiController::class, 'show'])
    ->whereIn('resource', [
        'pelanggan',
        'kendaraan',
        'daftar-service',
        'data-service',
        'pembayaran-service'
    ]);

// PUT: Memperbarui data
Route::put('/{resource}/{id}', [ServiceMotorApiController::class, 'update'])
    ->whereIn('resource', [
        'pelanggan',
        'kendaraan',
        'daftar-service',
        'data-service',
        'pembayaran-service'
    ]);

// DELETE: Menghapus data
Route::delete('/{resource}/{id}', [ServiceMotorApiController::class, 'destroy'])
    ->whereIn('resource', [
        'pelanggan',
        'kendaraan',
        'daftar-service',
        'data-service',
        'pembayaran-service'
    ]);
