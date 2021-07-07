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
        return  Major::all('title', 'icone');
    }


    public function get_major_des($id)
    {
        return  Major::findOrFail($id)->get(['media', 'text']);
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
        $major = $this->find($id);
        if ($major->icon != null) {
            unlink(public_path() . "/" . $major->icone);
        }

        if ($major->media != null) {
            unlink(public_path() . "/" . $major->media);
        }
        return Major::destroy($id);
    }
}