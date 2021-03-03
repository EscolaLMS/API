<?php

use App\Http\Controllers\API\AuthApiController;
use App\Http\Controllers\API\LoginApiController;
use App\Http\Controllers\API\LogoutApiController;
use App\Http\Controllers\API\RegisterApiController;
use Illuminate\Support\Facades\Route;

Route::post('login', [LoginApiController::class, 'login'])->name('login.api');
Route::post('register', [RegisterApiController::class, 'register'])->name('register.api');

Route::group(['prefix' => 'password'], function () {
    Route::post('forgot', [AuthApiController::class, 'forgotPassword']);
    Route::post('reset', [AuthApiController::class, 'resetPassword']);
});

Route::group(['prefix' => 'social'], function () {
    Route::get('{provider}', [AuthApiController::class, 'socialRedirect']);
    Route::get('{provider}/callback', [AuthApiController::class, 'socialCallback']);
});

Route::group(['prefix' => 'email'], function () {
    Route::get('verify/{id}/{hash}', [AuthApiController::class, 'verifyEmail'])->name('verification.verify');
    Route::post('resend', [AuthApiController::class, 'resendEmailVerification'])->middleware(['auth:api', 'throttle:6,1'])->name('verification.send');
});

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('logout', [LogoutApiController::class, 'logout'])->name('logout.api');
    Route::get('refresh', [AuthApiController::class, 'refresh']);
});
