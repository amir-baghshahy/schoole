<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MajorResource;
use App\Repositories\MajorRepository;
use Illuminate\Support\Facades\Validator;

class AdminMajorController extends Controller
{
    protected $repository;

    public function __construct(MajorRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $major =  $this->repository->get_majors_title();
        return  new MajorResource($major);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icone' => 'required|mimes:png,jpg,jpeg|max:4048',
            'text' => 'required|string',
            'media' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $requestdata = $this->upload($request->only(['title', 'icone', 'text', 'media']));
        $create = $this->repository->create($requestdata);

        if ($create) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icone' => 'required|mimes:png,jpg,jpeg|max:4048',
            'text' => 'required|string',
            'media' => 'required'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $requestdata = $this->upload($request->only(['id', 'title', 'icone', 'text', 'media']));
        $major = $this->repository->find($request->id);
        $update = $this->repository->update($major, $requestdata);

        if ($update) {
            return (new MajorResource($major))->additional([
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


    public function upload($request)
    {

        $icone = $request['icone'];
        $icone_name = "images/majors/" . time() . '_' . $icone->getClientOriginalName();
        $location_icone = public_path('images/majors');
        $icone->move($location_icone, $icone_name);

        $media = $request['media'];
        $media_name = "media/" . time() . '_' . $media->getClientOriginalName();
        $location_media = public_path('media');
        $media->move($location_media, $media_name);


        $requestdata = $request;
        $requestdata['icone'] = $icone_name;
        $requestdata['media'] = $media_name;

        return $requestdata;
    }
}