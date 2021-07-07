<?php

namespace App\Repositories;

use App\Models\Staff;

class StaffRepository
{

    public function all()
    {
        return Staff::with('user');
    }

    public function find($userid)
    {
        return  Staff::with('user')->where(['user_id' => $userid])->firstOrFail();
    }

    public function create($request)
    {
        return  Staff::create($request);
    }

    public function update($staff, $request)
    {
        return  $staff->update($request);
    }

    public function delete($id)
    {
        return Staff::destroy($id);
    }
}