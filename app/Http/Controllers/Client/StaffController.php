<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Repositories\StaffRepository;
use Illuminate\Http\Request;

class StaffController extends Controller
{

    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new StaffResource($this->repository->all());
    }
}