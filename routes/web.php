<?php

use App\Models\User;
use Hekmatinasser\Verta\Facades\Verta;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return response('schoole project');
});

Route::get('/test', function () {
    $users = User::all();
    foreach ($users as $user) {
        $birthday = Verta::parse($user->account->birthday);
        if ($birthday->isBirthday()) {
            echo ($birthday);
        } else {
            echo ($birthday);
        }
    }
});