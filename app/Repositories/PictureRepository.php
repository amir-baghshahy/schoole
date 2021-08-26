<?php

namespace App\Repositories;

use App\Models\About;
use App\Models\Album;
use App\Models\Picture;

class PictureRepository
{

    public function create($request)
    {
        return Picture::create($request);
    }

    public function update($request)
    {
        return Picture::findOrFail($request['id'])->update($request);
    }

    public function delete($id)
    {
        return Picture::findOrFail($id)->delete();
    }
}