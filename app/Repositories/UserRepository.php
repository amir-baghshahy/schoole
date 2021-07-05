<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function finduser($id)
    {
        return  User::findOrFail($id);
    }

    public function get_teachars()
    {
        return  User::all()->where(['role', '1']);
    }


    public function get_students()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'accepted'], ['archive', '0']])->paginate(30);
    }

    public function get_not_accepted()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'not-accepted'], ['archive', '0']])->paginate(30);
    }

    public function get_wait_accepted()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'wating-accepted'], ['archive', '0']])->paginate(30);
    }

    public function get_incomplete_info()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'incomplete-information']])->paginate(30);
    }


    public function get_all($request)
    {
        $code = $request->query('code');
        $family = $request->query('family');
        $grade = $request->query('grade');
        $major_name = $request->query('major');

        if ($code) {
            return  User::whereHas(
                'account',
                function ($q) use ($code) {
                    return $q->where('national_code', $code);
                }
            )->with('account')->where([['role', '=', '2'], ['archive', '0']])->get();
        } elseif ($family) {
            return User::whereHas(
                'account',
                function ($q) use ($family) {
                    return $q->where('family', 'LIKE', '%' . $family . '%');
                }
            )->with('account')->where([['role', '=', '2'], ['archive', '0']])->get();
        } elseif ($grade) {
            return  User::whereHas(
                'account',
                function ($q) use ($grade) {
                    return $q->where('grade', $grade);
                }
            )->with('account')->where([['role', '=', '2'], ['archive', '0']])->get();
        } elseif ($major_name) {
            return  User::whereHas(
                'account',
                function ($q) use ($major_name) {
                    return $q->where('major_name', 'LIKE', '%' . $major_name . '%');
                }
            )->with('account')->where([['role', '=', '2'], ['archive', '0']])->get();
        } elseif ($grade && $major_name) {
            return  User::whereHas(
                'account',
                function ($q) use ($major_name) {
                    return $q->where([['major_name', 'LIKE', '%' . $major_name . '%'], ['grade', $grade]]);
                }
            )->with('account')->where([['role', '=', '2'], ['archive', '0']])->get();
        } else {
            return  User::with('account')->where([['role', '2'], ['archive', '0']])->paginate(30);
        }
    }

    public function get_archives()
    {
        return  User::with('account')->where([['role', '=', '2'], ['archive', '1']])->paginate(30);
    }

    public function create($request)
    {
        return  User::create($request);
    }

    public function update($user, $request)
    {
        return  $user->update($request);
    }

    public function getall()
    {
        return  User::with('account')->paginate(30);
    }

    public function delete($id)
    {
        $user = $this->finduser($id);
        $user->account()->delete();
        return $user->delete();
    }
}