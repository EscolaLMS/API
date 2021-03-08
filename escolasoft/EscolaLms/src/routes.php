<?php

use EscolaLms\Core\Http\Controllers\AttachmentAPIController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['api', 'auth:api'], 'prefix' => 'api/attachments'], function () {
    Route::post('/', [AttachmentAPIController::class, 'store']);
});
