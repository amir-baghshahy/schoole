<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\NewsRepository;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $repository;

    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        $news =  $this->repository->getall();
        return  new NewsResource($news);
    }

    public function find($id)
    {
        $news =  $this->repository->find($id);
        return new NewsResource($news);
    }
}