<?php

use App\Http\Controllers\Admin\AdminAboutController;
use App\Http\Controllers\Admin\AdminAccountController;
use App\Http\Controllers\Admin\AdminAlbumController;
use App\Http\Controllers\Admin\AdminDisciplineController;
use App\Http\Controllers\Admin\AdminEventController;
use App\Http\Controllers\Admin\AdminFileController;
use App\Http\Controllers\Admin\AdminMajorController;
use App\Http\Controllers\Admin\AdminMessageController;
use App\Http\Controllers\Admin\AdminNewsController;
use App\Http\Controllers\Admin\AdminPictureController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminSliderController;
use App\Http\Controllers\Admin\AdminStaffController;
use App\Http\Controllers\Admin\AdminStatisticController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Client\AboutController;
use App\Http\Controllers\Client\AccountController;
use App\Http\Controllers\Client\AlbumController;
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


Route::prefix('auth')->group(function () {
    
     Route::post('/login', [AuthController::class, 'login'])->name('login');
     Route::post('/register', [AuthController::class, 'register'])->middleware(['register_off','web_off']);
    
     Route::group(['middleware' => 'web_off'], function(){
        Route::get('about/all', [AboutController::class, 'index']);
        Route::get('slider/all', [SliderController::class, 'index']);
        Route::get('major/all', [MajorController::class, 'index']);
        Route::get('major/{id}', [MajorController::class, 'get_descroption']);
        Route::get('news/all', [NewsController::class, 'index']);
        Route::get('news/{id}', [NewsController::class, 'find']);
        Route::get('file/all', [FileController::class, 'getall']);
        Route::get('file/{id}', [FileController::class, 'find']);
        Route::get('staff/all', [StaffController::class, 'index']);
        Route::get('album/all', [AlbumController::class, 'all']);
        Route::get('album/{id}', [AlbumController::class, 'find']);
     });


});



Route::middleware(['auth:sanctum', 'json.response'])->group(function () {

    // client

      Route::post('/auth/logout', [AuthController::class, 'logout']);
      Route::get('user/info', [UserController::class, 'index']);
      Route::post('messages/create', [MessageController::class, 'create'])->middleware('web_off');
      Route::get('discipline', [DisciplineController::class, 'index'])->middleware('web_off');


    Route::prefix("user")->middleware('web_off')->group(function () {
        Route::put('/update', [UserController::class, 'update']);
        Route::get('/account/info', [AccountController::class, 'index']);
        Route::put('/account/update', [AccountController::class, 'update']);
    });


    Route::put('staff/update', [StaffController::class, 'update'])->middleware('staff_or_admin');
    
    Route::prefix('admin/file')->middleware('staff_or_admin')->group(function () {
        Route::get('all', [AdminFileController::class, 'getall']);
        Route::get('staff/all', [AdminFileController::class, 'staff_all']);
        Route::post('create', [AdminFileController::class, 'create']);
        Route::put('update', [AdminFileController::class, 'update']);
        Route::delete('delete/{id}', [AdminFileController::class, 'delete']);
    });



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
                Route::post('archive/{id}', [AdminUserController::class, 'archive']);
                Route::put('grade/up', [AdminUserController::class, 'grade_up']);
            });


            Route::prefix('staff')->group(function () {
                Route::get('all', [AdminStaffController::class, 'index']);
                Route::post('create', [AdminStaffController::class, 'create']);
                Route::put('update', [AdminStaffController::class, 'update']);
                Route::delete('delete/{id}', [AdminStaffController::class, 'delete']);
            });
        });


        Route::get('statics', [AdminStatisticController::class, 'statics']);

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
            Route::get('about/all', [AboutController::class, 'index']);
            Route::post('create', [AdminAboutController::class, 'create']);
            Route::put('update', [AdminAboutController::class, 'update']);
        });

        Route::prefix("discipline")->group(function () {
            Route::get('all', [AdminDisciplineController::class, 'get_all']);
            Route::get('find/{id}', [AdminDisciplineController::class, 'get_all']);
            Route::post('create', [AdminDisciplineController::class, 'create']);
            Route::put('update', [AdminDisciplineController::class, 'update']);
            Route::delete('delete/{id}', [AdminDisciplineController::class, 'delete']);
        });


        Route::prefix("settings")->group(function () {
            Route::get('all', [AdminSettingController::class, 'all']);
            Route::put('web', [AdminSettingController::class, 'switch_website']);
            Route::put('register', [AdminSettingController::class, 'switch_register']);
        });


        Route::prefix("events")->group(function () {
            Route::get('students/birthdays', [AdminEventController::class, 'get_student_birthday']);
            Route::get('teachers/birthdays', [AdminEventController::class, 'get_teacher_birthday']);
        });


        Route::prefix('album')->group(function () {
            Route::get('/all', [AdminAlbumController::class, 'index']);
            Route::get('album/{id}', [AdminAlbumController::class, 'find']);
            Route::post('/create', [AdminAlbumController::class, 'create']);
            Route::put('/update', [AdminAlbumController::class, 'update']);
            Route::delete('/delete', [AdminAlbumController::class, 'delete']);
        });


        Route::prefix('picture')->group(function () {
            Route::post('/create', [AdminPictureController::class, 'create']);
            Route::put('/update', [AdminPictureController::class, 'update']);
            Route::delete('/delete', [AdminPictureController::class, 'delete']);
        });
    });
});
