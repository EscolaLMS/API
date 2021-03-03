<?php

use App\Http\Controllers\API\ProfileAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/me', [ProfileAPIController::class, 'me']);
Route::put('/me', [ProfileAPIController::class, 'update']);
Route::put('/me-auth', [ProfileAPIController::class, 'updateAuthData']);
Route::put('/password', [ProfileAPIController::class, 'updatePassword']);
Route::put('/interests', [ProfileAPIController::class, 'interests']);
Route::get('/settings', [ProfileAPIController::class, 'settings']);
Route::put('/settings', [ProfileAPIController::class, 'settingsUpdate']);
Route::post('/upload-avatar', [ProfileAPIController::class, 'uploadAvatar']);
Route::delete('/delete-avatar', [ProfileAPIController::class, 'deleteAvatar']);

Route::put('/{id}', [ProfileAPIController::class, 'update']); // deprecated
Route::put('/{id}/interests', [ProfileAPIController::class, 'interests']); // deprecated
