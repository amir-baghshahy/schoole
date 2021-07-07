<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Major;

class AccountRepository
{

    public function find($userid)
    {
        return  Account::with('user')->where(['user_id' => $userid])->firstOrFail();
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