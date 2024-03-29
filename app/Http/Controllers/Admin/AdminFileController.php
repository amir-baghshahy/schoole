<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
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

    public function staff_all()
    {
        return new FileResource($this->repository->get_all_staff(auth()->user()->id));
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

        if ($this->repository->create($request_data)) {
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
            'file' => 'nullable'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('file')) {
            $request_data = $this->upload_file($request->toArray());
        } else {
            $request_data = $request->except(['file']);
        }

        if ($this->repository->update($request_data)) {
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

        if (auth()->user()->role == 0) {
            $delete = $this->repository->delete($id);
        } else {
            $delete = $this->repository->delete_check_user_id($id, auth()->user()->id);
        }

        if ($delete) {
            return response(['status' => true]);
        }

        return response(['status' => false]);
    }

    public function upload_file($request)
    {
        $file = $request['file'];
        if ($request['file']) {
            $filename = 'media/' . Str::random(20) . '_' . $file->getClientOriginalName();
            $location = public_path('media');
            $file->move($location, $filename);
            // $filename =  Storage::disk('public')->put("files", $request['file']);

            $request_data = $request;
            // $request_data['file'] = 'storage/' . $filename;
            $request_data['file'] =  $filename;

            return $request_data;
        }
    }
}