<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;



Route::middleware(['auth:sanctum', 'role.admin'])->group(function () {
    Route::get('/a/companies', [CompanyController::class, 'index'])->name('companies');
    Route::get('/a/companies/{company}/structure', [CompanyController::class, 'getStructureByCompanyId'])
        ->name('companies.structure.get');

    Route::post('/a/companies/{company}/structure', [CompanyController::class, 'storeStructure'])
        ->name('companies.structure.store');
});

