<?php

use App\Http\Controllers\EventAPIController;
use EscolaLms\Consultations\Enum\ConsultationTermStatusEnum;
use EscolaLms\Consultations\Models\ConsultationUserPivot;
use Illuminate\Support\Facades\Route;
use Spatie\Health\Http\Controllers\HealthCheckJsonResultsController;

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

Route::get('name', function () {
    return 'Application Name: ' . env('APP_NAME');
});

Route::get('events', [EventAPIController::class, 'index']);

Route::get('health', [\Spatie\Health\Http\Controllers\SimpleHealthCheckController::class, "__invoke"]);
Route::get('healthcheck',  [\Spatie\Health\Http\Controllers\HealthCheckJsonResultsController::class, "__invoke"]);
