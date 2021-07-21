<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Major;
use App\Models\Staff;

class AccountRepository
{

    public function find($user)
    {
        if (isset($user->role)) {
            if ($user->role == 1 || $user->role == 0) {
                return  Staff::with('user')->where(['user_id' => $user->id])->first();
            }
        } else {
            return  Account::with('user')->where(['user_id' => $user])->first();
        }
    }

    public function create($request)
    {
        return  Account::create($request);
    }

    public function update($account, $request)
    {
        return  $account->update($request);
    }

    public function delete($id)
    {
        return Account::destroy($id);
    }
}