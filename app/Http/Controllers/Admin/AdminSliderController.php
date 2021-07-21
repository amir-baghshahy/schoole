<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\Validator;

class AdminSliderController extends Controller
{

    protected $repository;

    public function __construct(SliderRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $slides =  $this->repository->getall();

        return  new SliderResource($slides);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'link' => 'nullable|max:255',
            'description' => 'nullable',
            'img' => 'required|mimes:png,jpg,jpeg',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }


        $requestdata = $this->upload_image($request->only(['title', 'link', 'description', 'img']));

        $create = $this->repository->create($requestdata);

        if ($create) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {
        if ($request->method() == "PUT") {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|max:255',
                'id' => 'required|string|max:255',
                'link' => 'nullable|max:255',
                'description' => 'nullable|max:255',
                'img' => 'nullable',
            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'title' => 'nullable|max:255',
                'id' => 'required|string|max:255',
                'link' => 'nullable|max:255',
                'description' => 'nullable|max:255',
                'img' => 'required|mimes:png,jpg,jpeg',
            ]);
        }


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('img')) {
            $requestdata = $this->upload_image($request->only(['id', 'title', 'link', 'description', 'img']));
        } else {
            $request_data = $request;
        }


        $slider = $this->repository->findslide($request->id);
        $update = $this->repository->update($slider, $requestdata);

        if ($update) {
            return (new SliderResource($slider))->additional([
                "status" => $update
            ]);
        }
        return response(['status' => false]);
    }

    public function delete($id)
    {
        $delete  = $this->repository->delete($id);

        if ($delete) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function upload_image($request)
    {
        $file = $request['img'];
        $filename = "images/sliders/" . time() . '_' . $file->getClientOriginalName();
        $location = public_path('images/sliders');
        $file->move($location, $filename);

        $request_data = $request;
        $request_data['img'] = $filename;

        return $request_data;
    }
}