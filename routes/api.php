<?php

use App\Http\Controllers\API\ShareApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::group(['prefix' => 'courses', 'middleware' => ['auth:api', 'role:instructor,admin']], function () {
    require 'api/admin.php';
});


Route::group(['prefix' => 'tags'], function () {
    require 'api/tags.php';
});

Route::middleware('auth:api')->prefix('share')->group(function () {
    Route::get('linkedin', [ShareApiController::class, 'linkedin']);
    Route::get('facebook', [ShareApiController::class, 'facebook']);
    Route::get('twitter', [ShareApiController::class, 'twitter']);
});

Route::group(['prefix' => 'courses'], function () {
    require 'api/courses.php';
});



Route::get('/debug-sentry', function () {
    throw new Exception('Test Sentry error!');
});

Route::group(['prefix' => 'cart', 'middleware' => ['auth:api']], function () {
    require 'api/cart.php';
});
