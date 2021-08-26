<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Repositories\AlbumRepository;
use Illuminate\Http\Request;

class AlbumController extends Controller
{

    protected $repository;

    public function __construct(AlbumRepository $repository)
    {
        $this->repository = $repository;
    }

    public function all()
    {
        return new AlbumResource($this->repository->index());
    }

    public function find($id)
    {
        $album = $this->repository->find($id);
        $pictures = $album->pictures;

        return new AlbumResource($pictures);
    }
}