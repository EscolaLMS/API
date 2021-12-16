<?php

// use App\Http\Controllers\SettingsController;

use Illuminate\Support\Facades\Auth;
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

Route::get('/debug-sentry', function () {
    throw new Exception('Test Sentry error!');
});

Route::group(['middleware' => ['auth:token']], function () {
    Route::get('/abc', function () {
        $u = Auth::user();

        return response()->json($u);
    });
}
);

// Route::get('/settings', SettingsController::class);
