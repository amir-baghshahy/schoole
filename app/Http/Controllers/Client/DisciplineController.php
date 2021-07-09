<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\DisciplineResource;
use App\Repositories\DisciplineRepository;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    protected $repository;

    public function __construct(DisciplineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $user = auth()->user();

        if ($user->status != 'accepted') {
            return response("Forribden", 403);
        }

        $result = $this->repository->findbyuser($user->id);

        return new DisciplineResource($result);
    }
}