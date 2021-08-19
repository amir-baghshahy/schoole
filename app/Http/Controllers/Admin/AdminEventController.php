<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Hekmatinasser\Verta\Facades\Verta;

class AdminEventController extends Controller
{
    public function get_student_birthday()
    {
        $result = [];

        $users = User::whereHas(
            'account',
            function ($q) {
                return $q->whereNotNull('birthday');
            }
        )->with('account')->where([['role', '=', '2'], ['archive', false], ['status', '=', 'accepted']])->get();
        foreach ($users as $user) {
            $birthday = Verta::parse($user->account->birthday);
            if ($birthday->isBirthday()) {
                $result[] = $user;
            }
        }

        return new UserResource($result);
    }

    public function get_teacher_birthday()
    {
        $result = [];

        $users = User::whereHas(
            'staff',
            function ($q) {
                return $q->whereNotNull('birthday');
            }
        )->with('staff')->where([['role', '<=>', '2']])->get();
        foreach ($users as $user) {
            $birthday = Verta::parse($user->staff->birthday);
            if ($birthday->isBirthday()) {
                $result[] = $user;
            }
        }

        return new UserResource($result);
    }
}