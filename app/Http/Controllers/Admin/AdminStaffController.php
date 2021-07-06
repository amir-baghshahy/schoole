<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Repositories\StaffRepository;
use Illuminate\Http\Request;

class AdminStaffController extends Controller
{
    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $result =  $this->repository->all()->active();
        return  new StaffResource($result);
    }
}