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

        $result = $this->repository->create($request->toArray());

        if ($result) {
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

        $about = $this->repository->find($request->id);

        $result = $this->repository->update($about, $request->except(['id']));

        if ($result) {
            return (new AboutResource($about))->additional([
                "status" => true
            ]);
        }
    }
}