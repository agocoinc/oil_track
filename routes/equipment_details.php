<?php

use App\Http\Controllers\EquipmentDetailsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/equipment-details/{category}', [EquipmentDetailsController::class, 'index'])->name('equipment-details.index');
    Route::get('/equipment-detail/{detail}', [EquipmentDetailsController::class, 'show'])->name('equipment-details.show');
});


Route::middleware(['auth:sanctum', 'role.user'])->group(function () {
    Route::get('/equipment-details-stats', [EquipmentDetailsController::class, 'stats'])->name('equipment-details.stats');
    Route::post('/equipment-details', [EquipmentDetailsController::class, 'store'])->name('equipment-details.store');
    Route::delete('/equipment-details/{detail}', [EquipmentDetailsController::class, 'destroy'])->name('equipment-details.destroy');
});

Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::get('/equipment-details-stats-admin', [EquipmentDetailsController::class, 'statsForAdmin'])->name('equipment-details.statsForAdmin');
});
