<?php

use App\Http\Controllers\EquipmentCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return response()->json([
        'hello' => 'masoud almashay'
    ]);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/equipment_category.php';
require __DIR__.'/equipment_details.php';
