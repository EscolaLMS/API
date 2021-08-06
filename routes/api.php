<?php

use App\Http\Controllers\API\ShareApiController;
// use App\Http\Controllers\SettingsController;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ScormController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('/debug-sentry', function () {
    throw new Exception('Test Sentry error!');
});


Route::get('/settings', SettingsController::class);

