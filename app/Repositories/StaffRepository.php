<?php

namespace App\Repositories;

use App\Models\Staff;

class StaffRepository
{

    public function all()
    {
        return Staff::all()->where('status', '1')->get();
    }

    public function find($userid)
    {
        return  Staff::where(['user_id' => $userid])->firstOrFail();
    }

    public function create($request)
    {
        return  Staff::create($request);
    }

    public function update($account, $request)
    {
        return  $account->update($request);
    }

    public function delete($id)
    {
        return Staff::destroy($id);
    }
}