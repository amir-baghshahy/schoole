<?php

namespace App\Repositories;

use App\Models\Major;

class MajorRepository
{

    public function find($id)
    {
        return  Major::findOrFail($id);
    }

    public function get_majors_title()
    {
        return  Major::all('title', 'icone');
    }


    public function get_major_des($id)
    {
        return  Major::where('id', $id)->get(['media', 'text']);
    }

    public function create($request)
    {
        return  Major::create($request);
    }

    public function update($major, $request)
    {
        return  $major->update($request);
    }

    public function delete($id)
    {
        return Major::destroy($id);
    }
}