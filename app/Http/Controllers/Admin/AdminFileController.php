<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminFileController extends Controller
{
    protected $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getall()
    {
        return new FileResource($this->repository->getall());
    }

    public function create(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'file' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload_file($request->toArray());
        $request_data['user_id'] = auth()->user()->id;

        $create = $this->repository->create($request_data);

        if ($create) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => "required",
            'title' => 'required',
            'file' => 'string'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload_file($request->toArray());
        $update = $this->repository->update($request_data);

        if ($update) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }


    public function delete($id)
    {

        $file = $this->repository->find($id);

        if ($file->file && file_exists(public_path() . "/" . $file->file)) {
            unlink(public_path() . "/" . $file->file);
        }

        $delete  = $this->repository->delete($id);

        if ($delete) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function upload_file($request)
    {
        $file = $request['file'];
        if ($request['file']) {
            $filename = "media/" . time() . '_' . $file->getClientOriginalName();
            $location = public_path('media');
            $file->move($location, $filename);

            $request_data = $request;
            $request_data['file'] = $filename;

            return $request_data;
        }
    }
}