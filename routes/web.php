<?php


use Illuminate\Support\Facades\Route;



Route::get('/', function () {
    return 'schoole project';
});


// Route::get('/test', function () {
    // $user = User::find(1);
    // Notification::send($user, new NewUserNotification($user));

    // return ($user->unreadNotifications->count());
    // $user->unreadNotifications->markAsRead();
    // return DatabaseNotification::all();
// });