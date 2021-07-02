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
        $news =  $this->repository->getall();
        return  NewsResource::collection($news);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg|max:4048',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }


        if ($request->file('image')) {

            $requestdata = $this->upload_image($request->only(['title', 'text', 'image']));
            $requestdata['user_id'] = auth()->user()->id;

            $create = $this->repository->create($requestdata);

            if ($create) {
                return response(['status' => true]);
            } else {
                return response(['status' => false]);
            }
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'equired|string',
            'title' => 'required|string|max:255',
            'text' => 'required|string',
            'image' => 'required|mimes:png,jpg,jpeg|max:4048',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }
        if ($request->file('image')) {

            $requestdata = $this->upload_image($request->only(['id', 'title', 'text', 'image']));

            $news = $this->repository->find($request->id);
            $update = $this->repository->update($news, $requestdata);

            if ($update) {
                return (new NewsResource($news))->additional([
                    "status" => $update
                ]);
            }
            return response(['status' => false]);
        }
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
        $file = $request['image'];
        $filename = "images/news/" . time() . '_' . $file->getClientOriginalName();
        $location = public_path('images/news');
        $file->move($location, $filename);

        $requestdata = $request;
        $requestdata['image'] = $filename;

        return $request;
    }
}