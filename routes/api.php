<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;

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

Route::prefix('v1')->name('api.v1.')->group(function() {
    Route::delete('category/{uuid}',[CategoryController::class,'destroy']);
    Route::get('category', [CategoryController::class,'show'])->name('category.index');

    Route::post('category',[CategoryController::class,'store']);
});

Route::prefix('v2')->name('api.v2.')->group(function() {
    Route::get('version', function() {
        return 'this is version v2';
    })->name('version');
});

Route::fallback(function () {
    return Response()->json([
        'error' => true,
        'msg' => '请求错误',
        'data' => []
    ],400);
});

