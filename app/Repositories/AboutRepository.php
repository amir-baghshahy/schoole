<?php

namespace App\Repositories;

use App\Models\About;

class AboutRepository
{

    public function find($id)
    {
        return About::findOrFail($id);
    }

    public function index()
    {
        return  About::all();
    }

    public function create($request)
    {
        return  About::create($request);
    }

    public function update($about, $request)
    {
        return  $about->update($request);
    }
}