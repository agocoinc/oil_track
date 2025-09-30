<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])->name('companies');
    Route::get('/companies/{company}/structure', [CompanyController::class, 'getStructureByCompanyId'])
        ->name('companies.structure.get');

    Route::post('/companies/{company}/structure', [CompanyController::class, 'storeStructure'])
        ->name('companies.structure.store');
});

