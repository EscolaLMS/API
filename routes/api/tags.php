<?php

use App\Http\Controllers\API\TagAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/', [TagAPIController::class, 'index']);
Route::get('unique', [TagAPIController::class, 'unique']);
