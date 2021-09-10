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
        return  $this->find(1);
    }

    public function update($request)
    {
        return  About::find($request['id'])->update($request);
    }
}