<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MessageResource;
use App\Repositories\MessageRepository;

class AdminMessageController extends Controller
{
    protected $repository;

    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getall()
    {
        return new MessageResource($this->repository->getall());
    }

    public function delete($id)
    {
        if ($this->repository->delete($id)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }
}