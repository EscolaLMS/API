<?php

use App\Http\Controllers\API\CourseAPIController;
use App\Http\Controllers\API\CourseProgressAPIController;
use Illuminate\Support\Facades\Route;

Route::get('/', [CourseAPIController::class, 'index']);
Route::get('/related/{course}', [CourseAPIController::class, 'related']);
Route::get('/related-many', [CourseAPIController::class, 'relatedMany']);
Route::get('/search', [CourseAPIController::class, 'search']);
Route::get('/category/{category}', [CourseAPIController::class, 'category']);
Route::get('/popular', [CourseAPIController::class, 'popular']);
Route::get('/file', [CourseAPIController::class, 'file'])->name('file');

Route::middleware('auth:api')->group(function () {
    Route::get('/recommended', [CourseAPIController::class, 'recommended']);

    Route::group(['prefix' => 'progress'], function () {
        Route::get('/', [CourseProgressAPIController::class, 'index']);
        Route::get('/{course}', [CourseProgressAPIController::class, 'show']);
        Route::patch('/{course}', [CourseProgressAPIController::class, 'store']);
        Route::put('/{curriculum_lectures_quiz}/ping', [CourseProgressAPIController::class, 'ping']);
    });

    Route::get('{course}/curriculum', [CourseAPIController::class, 'curriculum']);
});

Route::get('{course}/forum', [CourseAPIController::class, 'showForum']);
Route::get('{course}', [CourseAPIController::class, 'show']);
