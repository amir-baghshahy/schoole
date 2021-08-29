<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AboutResource;
use App\Repositories\AboutRepository;
use Illuminate\Support\Facades\Validator;

class AdminAboutController extends Controller
{
    protected $repository;

    public function __construct(AboutRepository $repository)
    {
        $this->repository = $repository;
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->create($request->toArray())) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'text' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->update($request->toArray())) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }
}
