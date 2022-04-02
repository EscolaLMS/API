<?php

use EscolaLms\Consultations\Enum\ConsultationTermStatusEnum;
use EscolaLms\Consultations\Models\ConsultationUserPivot;
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
    throw new \Exception('Test Sentry error!');
});

Route::get('seeds/consultations/{author?}/{user?}', function ($author = null, $user = null) {
    $seed = new \EscolaLms\Consultations\Database\Seeders\ConsultationTermsSeeder($author, $user);
    $seed->run();
    return response()->json(['msg' => 'success']);
});

Route::group(['middleware' => ['auth:api'], 'prefix' => 'api'], function () {

});

// Route::get('/settings', SettingsController::class);
