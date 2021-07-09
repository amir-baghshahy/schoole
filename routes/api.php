<?php

use App\Http\Controllers\Admin\AdminAboutController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminDisciplineController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\Admin\AdminMajorController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\DisciplineController;
use App\Http\Controllers\Client\FileController;
use App\Http\Controllers\Client\MajorController;
use App\Http\Controllers\Client\MessageController;
use App\Http\Controllers\Client\NewsController;
use App\Http\Controllers\Client\SliderController;
use App\Http\Controllers\Client\StaffController;
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

        Route::get('staff/all', [StaffController::class, 'index']);
        Route::put('staff/update', [StaffController::class, 'update']);
    });

    Route::get('slider/all', [SliderController::class, 'index']);
    Route::get('major/all', [MajorController::class, 'index']);
    Route::get('major/{id}', [MajorController::class, 'get_descroption']);
    Route::get('news/all', [NewsController::class, 'index']);
    Route::get('news/{id}', [NewsController::class, 'find']);
    Route::post('messages/create', [MessageController::class, 'create']);
    Route::get('about/all', [AboutController::class, 'index']);
    Route::get('discipline', [DisciplineController::class, 'index']);
    Route::get('file/all', [FileController::class, 'getall']);
    Route::get('file/get/{id}', [FileController::class, 'find']);


    // admin
    Route::prefix("admin")->middleware(['admin'])->group(function () {

        Route::prefix("user")->group(function () {

            Route::get('{status}', [AdminUserController::class, 'index']);

            Route::prefix('student')->group(function () {
                Route::post('create', [AdminUserController::class, 'create']);
                Route::delete('delete/{id}', [AdminUserController::class, 'delete']);
                Route::put('change/status', [AdminUserController::class, 'change_status']);

                Route::get('account/{id}', [AdminAccountController::class, 'find']);
                Route::put('update', [AdminAccountController::class, 'update']);
                Route::post('archive', [AdminUserController::class, 'archive']);
                Route::put('grade/up', [AdminUserController::class, 'grade_up']);
            });



            Route::prefix('staff')->group(function () {
                Route::get('all', [AdminStaffController::class, 'index']);
                Route::post('create', [AdminUserController::class, 'create_teacher']);
                Route::put('update', [AdminStaffController::class, 'update']);
                Route::delete('delete/{id}', [AdminUserController::class, 'delete']);
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


        Route::prefix("messages")->group(function () {
            Route::delete('delete/{id}', [AdminMessageController::class, 'delete']);
            Route::get('all', [AdminMessageController::class, 'getall']);
        });


        Route::prefix("about")->group(function () {
            Route::post('create', [AdminAboutController::class, 'create']);
            Route::put('update', [AdminAboutController::class, 'update']);
        });

        Route::prefix("discipline")->group(function () {
            Route::get('all', [AdminDisciplineController::class, 'get_all']);
            Route::post('create', [AdminDisciplineController::class, 'create']);
            Route::put('update', [AdminDisciplineController::class, 'update']);
            Route::delete('delete/{id}', [AdminDisciplineController::class, 'delete']);
        });


        Route::prefix("file")->group(function () {
            Route::get('all', [AdminFileController::class, 'getall']);
            Route::post('create', [AdminFileController::class, 'create']);
            Route::put('update', [AdminFileController::class, 'update']);
            Route::delete('delete/{id}', [AdminFileController::class, 'delete']);
        });
    });
});