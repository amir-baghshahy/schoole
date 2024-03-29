<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\SliderResource;
use App\Repositories\SliderRepository;
use Illuminate\Support\Facades\Storage;
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

        return  SliderResource::collection($slides);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'link' => 'nullable|max:255',
            'description' => 'nullable',
            'img' => 'required|image',
        ], [
            'img.image' => 'فایل وارد شده معتبر نمی باشد'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload_image($request->only(['title', 'link', 'description', 'img']));

        if ($this->repository->create($request_data)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|max:255',
            'id' => 'required|string|max:255',
            'link' => 'nullable|max:255',
            'description' => 'nullable|max:255',
            'img' => 'nullable|image',
        ], [
            'img.image' => 'فایل نامعتبر است '
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('img')) {
            $request_data = $this->upload_image($request->only(['id', 'title', 'link', 'description', 'img']));
        } else {
            $request_data = $request->only(['id', 'title', 'link', 'description']);
        }

        if ($this->repository->update($request_data)) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function delete($id)
    {
        $slider = $this->repository->findslide($id);

        if ($slider->img && file_exists(public_path() . "/" . $slider->img)) {
            unlink(public_path() . "/" . $slider->img);
        }

        if ($this->repository->delete($id)) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function upload_image($request)
    {
        $file = $request['img'];
        $filename = Str::random(20) . '_' . $file->getClientOriginalName();
        $filename = "images/sliders/" . Str::random(20) . '_' . $file->getClientOriginalName();
        $location = public_path('images/sliders');
        $file->move($location, $filename);
        // $filename =  Storage::disk('public')->put("sliders", $request['img']);


        $request_data = $request;
        // $request_data['img'] = "storage/" . $filename;
        $request_data['img'] =  $filename;

        return $request_data;
    }
}