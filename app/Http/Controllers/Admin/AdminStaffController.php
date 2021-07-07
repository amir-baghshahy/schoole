<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Models\User;
use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Validator;

class AdminStaffController extends Controller
{
    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $result =  $this->repository->all()->get();
        return  new StaffResource($result);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "id" => 'required',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $request->id,
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'rolename' => 'required',
            'degree' => 'required',
            'teaching_experience' => 'nullable',
            'major' => 'required',
            'status' => 'nullable',
            'image' => 'required|mimes:png,jpg,jpeg',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = User::find($request->id);
        $user->update(['phone' => $request->phone, 'password' => $request->password]);

        $request_data = $this->upload_image($request->except(['id', 'phone', 'password']));
        $staff = $this->repository->find($user->id);
        $result = $this->repository->update($staff, $request_data);

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