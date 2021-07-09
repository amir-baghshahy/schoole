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
        $file = $this->find($id);
        if ($file->file) {
            unlink(public_path() . "/" . $file->file);
        }

        return File::destroy($id);
    }

    public function update($file, $request)
    {
        return  $file->update($request);
    }
}