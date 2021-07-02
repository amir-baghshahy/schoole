<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\MajorResource;
use App\Repositories\MajorRepository;
use Illuminate\Http\Request;

class MajorController extends Controller
{
    protected $repository;

    public function __construct(MajorRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        $major =  $this->repository->get_majors_title();

        return  MajorResource::collection($major);
    }


    public function get_descroption($id)
    {
        $major =  $this->repository->get_major_des($id);
        return  MajorResource::collection($major);
    }
}