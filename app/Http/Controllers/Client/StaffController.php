<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new StaffResource($this->repository->all()->active()->get());
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role != 1) {
            return response("Forbidden", 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'degree' => 'required',
            'teaching_experience' => 'nullable',
            'major' => 'required',
            'image' => 'string',
            'shabanumber' => 'nullable|digits:24',
            'birthday' => 'required'
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->file('image')) {
            $request_data = $this->upload_image($request->only(['name', 'family', 'degree', 'teaching_experience', 'major', 'image', 'shabanumber', 'birthday']));
        } else {
            $request_data = $request->only(['name', 'family', 'degree', 'teaching_experience', 'major', 'image', 'shabanumber', 'birthday']);
        }

        $request_data['id'] = auth()->user()->id;
        $result = $this->repository->update($request_data);

        if ($result) {
            return response(['status' => true], 200);
        } else {
            return response(['status' => false], 422);
        }
    }


    public function upload_image($request)
    {
        $file = $request['image'];
        $filename = "images/staff/" . time() . '_' . $file->getClientOriginalName();
        $location = public_path('images/staff');
        $file->move($location, $filename);

        $request_data = $request;
        $request_data['image'] = $filename;

        return $request_data;
    }
}