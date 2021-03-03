<?php

use App\Http\Controllers\API\ForumApiController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ForumApiController::class, 'index']);
Route::post('/', [ForumApiController::class, 'store']);
Route::get('categories', [ForumApiController::class, 'indexCategories']);
Route::get('{forumTopic}', [ForumApiController::class, 'show']);
Route::put('{forumTopic}', [ForumApiController::class, 'update']);
Route::delete('{forumTopic}', [ForumApiController::class, 'destroy']);
