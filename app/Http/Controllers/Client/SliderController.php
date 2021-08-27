<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\Validator;

class SliderController extends Controller
{


    protected $repository;

    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        $slides =  $this->repository->getall();

        return SliderResource::collection($slides);
    }
}