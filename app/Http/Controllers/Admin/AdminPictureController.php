<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\PictureRepository;
use Illuminate\Support\Facades\Validator;

class AdminPictureController extends Controller
{
    protected $repository;

    public function __construct(PictureRepository $repository)
    {
        $this->repository = $repository;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image',
            'album_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload_file($request->toArray());

        if ($this->repository->create($request_data)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'image' => 'nullable',
            'album_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('image')) {
            $request_data = $this->upload_image($request->only(['id', 'image', 'album_id']));
        } else {
            $request_data = $request->toArray();
        }

        if ($this->repository->update($request_data)) {
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

    public function upload_file($request)
    {
        $file = $request['image'];
        if ($request['image']) {
            $filename = 'pictures/' . time() . '_' . $file->getClientOriginalName();
            $location = public_path('pictures');
            $file->move($location, $filename);

            $request_data = $request;
            $request_data['image'] = $filename;

            return $request_data;
        }
    }
}