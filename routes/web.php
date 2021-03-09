<?php

use EscolaSoft\LaravelH5p\Http\Controllers\EmbedController;
use App\Http\Requests\H5pEmbedRequest;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('embed/{id}', function (H5pEmbedRequest $request, int $id) {
    $embedCtrl = app(EmbedController::class);
    return $embedCtrl($request, $id);
})->name('h5p.user.embed');
