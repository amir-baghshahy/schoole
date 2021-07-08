<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Repositories\AboutRepository;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    protected $repository;

    public function __construct(AboutRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        return new AboutResource($this->repository->index());
    }
}