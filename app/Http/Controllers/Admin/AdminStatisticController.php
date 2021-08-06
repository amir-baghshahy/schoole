<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Discipline;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class AdminStatisticController extends Controller
{
    public function statics()
    {
        $users = User::all();
        $user_online = 0;

        foreach ($users as $user) {
            if (Cache::has('online_user' . $user->id)) {
                $user_online++;
            }
        }

        $all_user =  User::where([['archive', '=', false], ['role', '=', 2]])->count();


        $incomplete_information = User::where([['archive', false], ['role', 2], ['status', 'incomplete-information']])->count();


        $not_accepted = User::where([['archive', '=', false], ['role', '=', '2'], ['status', '=', 'not-accepted']])->count();

        $archive = User::where([['archive', '=', true], ['role', '=', '2']])->count();

        $staff = User::where('role', '=', '1')->count();

        $student = User::where([['status', '=', 'accepted',], ['archive', '=', false], ['role', '=', '2']])->count();

        $wait_accept = User::where([['archive', '=', false], ['role', '=', '2'], ['status', '=', 'waiting-accepted']])->count();

        $grade_1 = User::whereHas(
            'account',
            function ($q) {
                return $q->where('grade', '=', '1');
            }
        )->where([['role', '=', '2'], ['archive', '=', false], ['status', '=', 'accepted']])->count();

        $grade_2 = User::whereHas(
            'account',
            function ($q) {
                return $q->where('grade', '=', '2');
            }
        )->where([['role', '=', '2'], ['archive', false], ['status', '=', 'accepted']])->count();


        $grade_3 = User::whereHas(
            'account',
            function ($q) {
                return $q->where('grade', '=', '3');
            }
        )->where([['role', '=', '2'], ['archive', '=', false], ['status', '=', 'accepted']])->count();

        $dicsipline = Discipline::all()->count();

        $user_create_today = User::whereDate('created_at', Carbon::today())->count();

        $user_Graduated =  User::whereHas(
            'account',
            function ($q) {
                return $q->where('grade', '=', '3');
            }
        )->where([['role', '=', '2'], ['archive', true], ['status', '=', 'accepted']])->count();


        return response(['data' => ['user_online' => $user_online, 'not_accepted' => $not_accepted, 'archive' => $archive, 'staff' => $staff, 'student' => $student, 'wait_accept' => $wait_accept, 'grade_1' => $grade_1, 'grade_2' => $grade_2, 'grade_3' => $grade_3, 'dicsipline' => $dicsipline, 'user_create_today' => $user_create_today, 'incomplete_information' => $incomplete_information, 'user_Graduated' => $user_Graduated, 'all_user' => $all_user]]);
    }
}