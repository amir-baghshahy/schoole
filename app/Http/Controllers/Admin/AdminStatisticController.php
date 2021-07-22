<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class AdminStatisticController extends Controller
{
    public function userOnlineStatus()
    {
        $users = User::all();
        $count = 0;

        foreach ($users as $user) {
            if (Cache::has('online_user' . $user->id)) {
                $count++;
            }
        }
        return $count;
    }
}