<?php

namespace App\Repositories;

use App\Models\Staff;

class StaffRepository
{

    public function all()
    {
        return Staff::with('user')->select(['name', 'family', 'rolename', 'degree', 'teaching_experience', 'image']);
    }

    public function all_admin()
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

    public function update($id, $request)
    {
        return Staff::where("user_id", $id)->update($request);
    }

    public function delete($id)
    {
        return Staff::destroy($id);
    }
}