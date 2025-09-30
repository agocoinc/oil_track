<?php

use App\Http\Controllers\EquipmentCategoryController;
use Illuminate\Support\Facades\Route;





Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/equipment-categories', [EquipmentCategoryController::class, 'index'])->name('equipment-categories.index');
    Route::get('/equipment-categories/{category}', [EquipmentCategoryController::class, 'show'])->name('equipment-categories.show');
});

Route::middleware(['auth:sanctum', 'role.user'])->group(function () {
    Route::post('/equipment-categories', [EquipmentCategoryController::class, 'store'])->name('equipment-categories.store');
    Route::delete('/equipment-categories/{category}', [EquipmentCategoryController::class, 'destroy'])->name('equipment-categories.destroy');
});


Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::get('/equipment-categories-all', [EquipmentCategoryController::class, 'getAll'])->name('equipment-categories.getAll');
});

