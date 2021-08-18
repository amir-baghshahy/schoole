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
        $major =  $this->repository->all();
        return  new MajorResource($major);
    }



    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'icone' => 'required|mimes:png,jpg,jpeg',
            'text' => 'required|string',
            'media' => 'required|mimes:png,jpg,jpeg,mp4,mov,ogg,ogx,oga,ogv,ogg,webm'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $this->upload($request->only(['title', 'icone', 'text', 'media']));
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
            'id' => 'required',
            'title' => 'required|string|max:255',
            'icone' => 'string',
            'text' => 'required|string',
            'media' => 'string'
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('media') && $request->file('icon')) {
            $request_data = $this->upload($request->only(['id', 'title', 'icone', 'text', 'media']));
        } elseif ($request->file('media')) {
            $request_data = $this->upload($request->only(['id', 'title', 'text', 'media']));
        } elseif ($request->file('icone')) {
            $request_data = $this->upload($request->only(['id', 'title', 'icone', 'text']));
        } else {
            $request_data = $request->only(['id', 'title', 'icone', 'text', 'media']);
        }

        $update = $this->repository->update($request_data);
        $major = $this->repository->find($request->id);

        if ($update) {
            return (new MajorResource($major))->additional([
                "status" => $update
            ]);
        }
        return response(['status' => false]);
    }




    public function delete($id)
    {
        $major = $this->repository->find($id);

        if ($major->icon != null && file_exists(public_path() . "/" . $major->icone)) {
            unlink(public_path() . "/" . $major->icone);
        }

        if ($major->media != null && file_exists(public_path() . "/" . $major->media)) {
            unlink(public_path() . "/" . $major->media);
        }

        $delete  = $this->repository->delete($id);

        if ($delete) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }




    public function upload($request)
    {
        $requestdata = $request;

        if (isset($request['icone'])) {
            $icone = $request['icone'];
            $icone_name = "images/majors/" . time() . '_' . $icone->getClientOriginalName();
            $location_icone = public_path('images/majors');
            $icone->move($location_icone, $icone_name);
            $requestdata['icone'] = $icone_name;
        }

        if (isset($request['media'])) {
            $media = $request['media'];
            $media_name = "media/" . time() . '_' . $media->getClientOriginalName();
            $location_media = public_path('media');
            $media->move($location_media, $media_name);
            $requestdata['media'] = $media_name;
        }

        return $requestdata;
    }
}