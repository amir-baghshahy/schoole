<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository
{

    public function findslide($id)
    {
        return Slider::findOrFail($id);
    }

    public function getall()
    {
        return  Slider::orderBy('created_at', 'asc')->get();
    }

    public function create($request)
    {
        return  Slider::create($request);
    }

    public function update($request)
    {
        return Slider::where('id', '=', $request['id'])->update($request);
    }

    public function delete($id)
    {
        return Slider::destroy($id);
    }
}