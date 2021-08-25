<?php

namespace App\Repositories;

use App\Models\File;

class FileRepository
{

    public function find($id)
    {
        return File::findOrFail($id);
    }

    public function index()
    {
        return  File::all();
    }

    public function getall()
    {
        return File::with('user.staff')->get();
    }

    public function create($request)
    {
        return  File::create($request);
    }

    public function delete($id)
    {
        return File::destroy($id);
    }

    public function update($request)
    {
        return File::find($request['id'])->update($request);
    }
}