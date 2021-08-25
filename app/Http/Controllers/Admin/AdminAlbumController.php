<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AlbumResource;
use App\Models\Album;
use App\Repositories\AlbumRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminAlbumController extends Controller
{

    protected $repository;

    public function __construct(AlbumRepository $repository)
    {
        $this->repository = $repository;
    }

    public function  index()
    {
        return new AlbumResource($this->repository->index());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'titile' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->create($request)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'titile' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->update($request)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function delete($id)
    {
    }
}