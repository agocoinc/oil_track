<?php

use App\Http\Controllers\EquipmentCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'hello' => 'masoud almashay'
    ]);
});

Route::middleware(['auth:sanctum'])->get('/me', function (Request $request) {
    return response()->json($request->user());
});

require __DIR__.'/settings.php';

require __DIR__.'/equipment_category.php';
require __DIR__.'/equipment_details.php';
require __DIR__.'/companies.php';
