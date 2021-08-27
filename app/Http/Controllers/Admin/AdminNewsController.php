<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\NewsResource;
use App\Repositories\NewsRepository;
use Illuminate\Support\Facades\Validator;

class AdminNewsController extends Controller
{
    protected $repository;

    public function __construct(NewsRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return  NewsResource::collection($this->repository->getall());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'image' => 'required|image',
        ], [
            'image.image' => 'فایل نامعتبر است'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload_image($request->only(['title', 'text', 'image']));
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
            'id' => 'required|string',
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'image' => 'nullable|image',
        ], [
            'image.image' => 'فایل نامعتبر است'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }
        if ($request->file('image')) {
            $request_data = $this->upload_image($request->only(['id', 'title', 'text', 'image']));
        } else {
            $request_data = $request->except(['image']);
        }

        if ($this->repository->update($request_data)) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function delete($id)
    {

        $news = $this->repository->find($id);

        if ($news[0]->image != null && file_exists(public_path() . "/" . $news[0]->image)) {
            unlink(public_path() . "/" . $news[0]->image);
        }

        if ($this->repository->delete($id)) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function upload_image($request)
    {
        $file = $request['image'];
        $filename = "images/news/" . time() . '_' . $file->getClientOriginalName();
        $location = public_path('images/news');
        $file->move($location, $filename);

        $request_data = $request;
        $request_data['image'] = $filename;
        $request_data['user_id'] = auth()->user()->id;

        return $request_data;
    }
}