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
        $news = $this->find($id);
        if ($news->image) {
            unlink(public_path() . "/" . $news->image);
        }
        return News::destroy($id);
    }
}