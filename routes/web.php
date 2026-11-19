<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ServiceMotorController;

Route::get('/', function () {
    return redirect()->route('service_motor.index', 'pelanggan');
});

Route::prefix('service-motor')->name('service_motor.')->group(function () {
    Route::get('/{resource}', [ServiceMotorController::class, 'index'])
        ->name('index');

    Route::get('/{resource}/create', [ServiceMotorController::class, 'create'])
        ->name('create');

    Route::post('/{resource}', [ServiceMotorController::class, 'store'])
        ->name('store');

    Route::get('/{resource}/{id}/edit', [ServiceMotorController::class, 'edit'])
        ->name('edit');

    Route::put('/{resource}/{id}', [ServiceMotorController::class, 'update'])
        ->name('update');

    Route::delete('/{resource}/{id}', [ServiceMotorController::class, 'destroy'])
        ->name('destroy');
});
