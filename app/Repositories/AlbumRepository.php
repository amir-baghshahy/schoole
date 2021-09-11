<?php

namespace App\Repositories;

use App\Models\About;
use App\Models\Album;

class AlbumRepository
{
    public function index()
    {
        return Album::all();
    }

    public function create($request)
    {
        return Album::create($request);
    }

    public function find($id)
    {
        return Album::findOrFail($id);
    }

    public function update($request)
    {
        return Album::find($request['id'])->update($request);
    }

    public function delete($id)
    {
        $albume = $this->find($id);
        $albume->pictures()->delete();
        return $albume->delete();
    }
}