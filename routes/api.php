<?php

use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminMajorController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\AccountController;
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
        Route::get('/account/info', [AccountController::class, 'index']);
        Route::put('/account/update', [AccountController::class, 'update']);
    });

    Route::get('slider/all', [SliderController::class, 'index']);
    Route::get('major/all', [MajorController::class, 'index']);
    Route::get('major/{id}', [MajorController::class, 'get_descroption']);
    Route::get('news/all', [NewsController::class, 'index']);
    Route::get('news/{id}', [NewsController::class, 'find']);


    // admin
    Route::prefix("admin")->middleware(['admin'])->group(function () {

        Route::prefix("user")->group(function () {

            Route::get('{status}', [AdminUserController::class, 'index']);

            Route::prefix('student')->group(function () {
                Route::post('create', [AdminUserController::class, 'create']);
                Route::delete('delete/{id}', [AdminUserController::class, 'delete']);
                Route::put('change/status', [AdminUserController::class, 'change_status']);

                Route::put('update', [AdminAccountController::class, 'update']);
                Route::post('archive', [AdminUserController::class, 'archive']);
                Route::put('grade/up', [AdminUserController::class, 'grade_up']);
            });


            Route::get('account/{id}', [AdminAccountController::class, 'find']);

            Route::prefix('teacher')->group(function () {
                Route::post('teacher/create', [AdminUserController::class, 'create_teacher']);
            });
        });


        Route::prefix("slider")->group(function () {
            Route::post('create', [AdminSliderController::class, 'create']);
            Route::put('update', [AdminSliderController::class, 'update']);
            Route::delete('delete/{id}', [AdminSliderController::class, 'delete']);
            Route::get('all', [AdminSliderController::class, 'index']);
        });


        Route::prefix("major")->group(function () {
            Route::post('create', [AdminMajorController::class, 'create']);
            Route::put('update', [AdminMajorController::class, 'update']);
            Route::delete('delete/{id}', [AdminMajorController::class, 'delete']);
            Route::get('all', [AdminMajorController::class, 'index']);
        });


        Route::prefix("news")->group(function () {
            Route::post('create', [AdminNewsController::class, 'create']);
            Route::put('update', [AdminNewsController::class, 'update']);
            Route::delete('delete/{id}', [AdminNewsController::class, 'delete']);
            Route::get('all', [AdminNewsController::class, 'index']);
        });
    });
});