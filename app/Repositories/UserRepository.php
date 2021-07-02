<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    public function finduser($id)
    {
        return  User::find($id);
    }


    public function get_teachars()
    {
        return  User::all()->where('role', '1');
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
        return  User::all();
    }

    public function delete($id)
    {
        return  User::destroy($id);
    }
}