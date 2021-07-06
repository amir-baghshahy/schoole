<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{

    public function find($id)
    {
        return News::findOrFail($id)->with(['user' => function ($q) {
            return  $q->select('name', 'id');
        }])->get();
    }

    public function getall()
    {
        return News::select('*')->with(['user' => function ($q) {
            return  $q->select('name', 'id');
        }])->paginate(30);
    }

    public function create($request)
    {
        return  News::create($request);
    }

    public function update($news, $request)
    {
        return  $news->update($request);
    }

    public function delete($id)
    {
        return News::destroy($id);
    }
}