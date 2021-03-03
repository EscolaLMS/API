<?php

use App\Http\Controllers\API\CategoryAPIController;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', [CategoryAPIController::class, 'index']);
Route::get('tree', [CategoryAPIController::class, 'tree']);
Route::get('{category}', [CategoryAPIController::class, 'show']);
