<?php

namespace App\Repositories;

use App\Models\Message;

class MessageRepository
{
    public function getall()
    {
        return Message::with(['user.account' => function ($q) {
            return $q->select('user_id', 'name', 'family', 'grade');
        }])->paginate(30);
    }

    public function create($request)
    {
        return  Message::create($request);
    }

    public function delete($id)
    {
        return Message::destroy($id);
    }
}