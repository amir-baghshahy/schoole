<?php

namespace App\Repositories;

use App\Models\News;

class NewsRepository
{

    public function find($id)
    {
        return News::findOrFail($id)->with('user')->get();
    }

    public function getall()
    {
        return News::select('*')->with(['user' => function ($q) {
            return  $q->select('id');
        }, "user.account" => function ($q) {
            return  $q->select('id', 'user_id', 'name', 'family');
        }])->orderBy('created_at', 'asc')->paginate(10);
    }

    public function create($request)
    {
        return  News::create($request);
    }

    public function update($request)
    {
        News::where('id', '=', $request['id'])->update($request);
    }

    public function delete($id)
    {
        return News::destroy($id);
    }
}