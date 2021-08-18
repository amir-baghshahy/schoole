<?php

namespace App\Repositories;

use App\Models\Major;

class MajorRepository
{

    public function find($id)
    {
        return  Major::findOrFail($id);
    }

    public function index()
    {
        return  Major::all('title', 'icone', 'id');
    }

    public function all()
    {
        return  Major::all();
    }

    public function get_major_des($id)
    {
        return  Major::findOrFail($id)->get(['media', 'text']);
    }

    public function create($request)
    {
        return  Major::create($request);
    }

    public function update($request)
    {
        return Major::where("id", $request['id'])->update($request);
    }

    public function delete($id)
    {
        return Major::destroy($id);
    }
}