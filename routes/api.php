<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VerificationCodesController;
use App\Http\Controllers\AuthorizationsController;
use App\Http\Middleware\StrictFormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\Category;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

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
    Route::get('index',[UserController::class,'index']);
    Route::delete('category/{category}',[CategoryController::class,'destroy']);
    Route::get('category',[CategoryController::class,'index'])->name('category.index');
    Route::get('category/{category}', [CategoryController::class,'show'])->name('category.show');

    Route::post('category',[CategoryController::class,'store'])->name('category.store');
    Route::put('category/{category}',[CategoryController::class,'update'])->name('category.update');

    // 用户相关
    Route::post('user',[UserController::class,'store'])->name('user.store');
    // 验证码
    Route::post('verificationCodes',[VerificationCodesController::class,'store'])->name('verificationCodes.store');

    // 登录
    Route::post('authorizations', [AuthorizationsController::class, 'store'])
                    ->name('authorizations.store');

    Route::middleware('auth:sanctum')->any('/aaa',function(Request $request){
        return $request->user();
    });
    Route::get('getToken',function(){
        $user = User::findorFail(1);
        // dd($user->tokens);
        // Sanctum::actingAs($user,['*']);
        return $user->createToken('test')->plainTextToken;
    });
});



Route::fallback(function () {
    return Response()->json([
        'error' => true,
        'msg' => '请求错误',
        'data' => []
    ],400);
});

