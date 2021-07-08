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
        $result = $this->repository->getall();

        return new MessageResource($result);
    }

    public function delete($id)
    {
        $result = $this->repository->delete($id);

        if ($result) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }
}