<?php

namespace App\Repositories;

use App\Models\About;
use App\Models\Discipline;

class DisciplineRepository
{
    public function create($request)
    {
        return  Discipline::create($request);
    }

    public function find($id)
    {
        return Discipline::findOrFail($id);
    }

    public function getall()
    {
        return Discipline::with('user.account')->orderBy('created_at', 'asc')->get();
    }

    public function update($request)
    {
        return Discipline::find($request['id'])->update($request);
    }

    public function delete($id)
    {
        return Discipline::destroy($id);
    }

    public function findbyuser($user_id)
    {
        return Discipline::with('user.account')->where('user_id', '=', $user_id)->get();
    }
}