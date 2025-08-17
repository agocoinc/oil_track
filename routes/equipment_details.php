<?php

use App\Http\Controllers\EquipmentDetailsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->prefix('/api')->group(function () {
    Route::get('/equipment-details/{category}', [EquipmentDetailsController::class, 'index'])->name('equipment-details.index');
    Route::get('/equipment-details/{detail}', [EquipmentDetailsController::class, 'show'])->name('equipment-details.show');

    Route::post('/equipment-details', [EquipmentDetailsController::class, 'store'])->name('equipment-details.store');
    Route::delete('/equipment-details/{detail}', [EquipmentDetailsController::class, 'destroy'])->name('equipment-details.destroy');
});
