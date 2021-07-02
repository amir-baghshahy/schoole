<?php

use App\Http\Controllers\Admin\AdminMajorController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\MajorController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\SliderController;
use App\Http\Controllers\Client\UserController;
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


Route::middleware(['json.response'])->prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register']);
});



Route::middleware(['auth:sanctum', 'json.response'])->group(function () {

    // client
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::prefix("user")->group(function () {
        Route::get('/info', [UserController::class, 'index']);
        Route::put('/update', [UserController::class, 'update']);
    });

    Route::get('slider/all', [SliderController::class, 'index']);
    Route::get('major/all', [MajorController::class, 'index']);
    Route::get('major/{id}', [MajorController::class, 'get_descroption']);
    Route::get('news/all', [NewsController::class, 'index']);
    Route::get('news/{id}', [NewsController::class, 'find']);


    // admin
    Route::prefix("admin")->middleware(['admin'])->group(function () {

        Route::prefix("slider")->group(function () {
            Route::get('/all', [AdminSliderController::class, 'index']);
            Route::post('/create', [AdminSliderController::class, 'create']);
            Route::put('/update', [AdminSliderController::class, 'update']);
            Route::delete('/delete/{id}', [AdminSliderController::class, 'delete']);
        });

        Route::prefix("user")->group(function () {
            Route::get('/all', [AdminUserController::class, 'index']);
            Route::get('/all/teachers', [AdminUserController::class, 'get_teachers']);
            Route::post('/create', [AdminUserController::class, 'create']);
            Route::put('/update', [AdminUserController::class, 'update']);
            Route::delete('/delete/{id}', [AdminUserController::class, 'delete']);
        });


        Route::prefix("major")->group(function () {
            Route::get('/all', [AdminMajorController::class, 'index']);
            Route::post('/create', [AdminMajorController::class, 'create']);
            Route::put('/update', [AdminMajorController::class, 'update']);
            Route::delete('/delete/{id}', [AdminMajorController::class, 'delete']);
        });


        Route::prefix("news")->group(function () {
            Route::get('/all', [AdminNewsController::class, 'index']);
            Route::post('/create', [AdminNewsController::class, 'create']);
            Route::put('/update', [AdminNewsController::class, 'update']);
            Route::delete('/delete/{id}', [AdminNewsController::class, 'delete']);
        });
    });
});