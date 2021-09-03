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

    public function find($id)
    {
        $album = $this->repository->find($id);
        $pictures = $album->pictures;

        return new AlbumResource($pictures);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string'
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
            'title' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->update($request->toArray())) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function delete($id)
    {
        if ($this->repository->delete($id)) {
            return response(['status' => true]);
        }

        return response(['status' => false]);
    }
}