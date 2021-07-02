<?php

namespace App\Repositories;

use App\Models\Slider;

class SliderRepository
{

    public function findslide($id)
    {
        return Slider::find($id);
    }

    public function getall()
    {
        return  Slider::all();
    }

    public function create($request)
    {
        return  Slider::create($request);
    }

    public function update($slider, $request)
    {
        return  $slider->update($request);
    }

    public function delete($id)
    {
        return Slider::destroy($id);
    }
}