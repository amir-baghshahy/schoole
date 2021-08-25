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

    public function update($request)
    {
        return Album::find($request->id)->update($request);
    }
}