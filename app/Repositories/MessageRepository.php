<?php

namespace App\Repositories;

use App\Models\Account;
use App\Models\Message;

class MessageRespository
{
    public function getall()
    {
        return Account::with(['users.accounts' => function ($q) {
            return $q->select('user_id', 'name', 'family');
        }]);
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