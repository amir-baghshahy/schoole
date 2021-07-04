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
        return  User::all()->where('role', '1');
    }


    public function get_students()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'accepted']])->paginate(30);
    }

    public function get_not_accepted()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'not-accepted']])->paginate(30);
    }

    public function get_wait_accepted()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'wating-accepted']])->paginate(30);
    }

    public function get_incomplete_info()
    {
        return  User::with('account')->where([['role', '=', '2'], ['status', '=', 'incomplete-information']])->paginate(30);
    }


    public function get_all()
    {
        return  User::with('account')->where([['role', '=', '2']])->paginate(30);
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