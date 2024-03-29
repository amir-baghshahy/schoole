<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Storage;
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
        $result =  $this->repository->all_admin()->get();
        return  new StaffResource($result);
    }


    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:11|min:11|unique:users',
            'password' => 'required|string|min:8',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'rolename' => 'required',
            'role' => "required",
            'degree' => 'required',
            'teaching_experience' => 'nullable',
            'major' => 'required',
            'status' => 'nullable',
            'image' => 'nullable|image',
            'shabanumber' => 'nullable|digits:24',
            'birthday' => 'required'
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
            'image.image' => 'فایل نامعتبر است '
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($request->role == 0) {
            if (auth()->user()->super_user == true) {
                $user = User::create(['phone' => $request->phone, 'password' => $request->password, 'role' => $request->role, 'status' => '', 'status_cause' => '']);
            } else {
                return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
            }
        }

        if ($request->file('image')) {
            $request_data = $this->upload_image($request->toArray());
        } else {
            $request_data  = $request->toArray();
        }


        $request_data['user_id'] = $user->id;
        $request_data['image'] = 'images/staff/defualt.png';

        $staff = $this->repository->create($request_data);

        if ($staff) {
            return response(['status' => true], 200);
        } else {
            return response(['status' => false], 422);
        }
    }


    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $request->id,
            'password' => 'nullable|string|min:8',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'role' => "required",
            'rolename' => 'required',
            'degree' => 'required',
            'teaching_experience' => 'nullable',
            'major' => 'required',
            'status' => 'nullable',
            'image' => 'nullable|image',
            'shabanumber' => 'nullable|digits:24',
            'birthday' => 'required'
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
            'image.image' => 'فایل نامعتبر است '
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = User::find($request->id);

        if ($user->super_user == true && !auth()->user()->super_user) {
            return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
        } else {
            if ($request->has('password')) {
                $user->update(['phone' => $request->phone, 'password' => $request->password, 'role' => $request->role]);
            } else {
                $user->update(['phone' => $request->phone, 'role' => $request->role]);
            }
        }

        if ($request->file('image')) {
            $request_data = $this->upload_image($request->except(['phone', 'password', 'role', '_method', 'id']));
        } else {
            $request_data = $request->except(["phone", "password", "role", '_method', 'id', 'image']);
        }

        $result = $this->repository->update($request->id, $request_data);

        if ($result) {
            return response(['status' => $result], 200);
        } else {
            return response(['status' => false], 422);
        }
    }


    public function delete($id)
    {

        $user = User::find($id);
        $staff = $this->repository->find($id);

        if ($user->super_user && !auth()->user()->super_user) {
            return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
        } else {
            if ($staff->image && file_exists(public_path() . '/' . $staff->image)) {
                if ($staff->image != 'images/staff/defualt.png') {
                    unlink(public_path() . '/' . $staff->img);
                }
            }
            $user->staff()->delete();
            return $user->delete();
        }
    }

    public function upload_image($request)
    {
        $file = $request['image'];
        $filename = "images/staff/" . Str::random(20) . '_' . $file->getClientOriginalName();
        $location = public_path('images/staff');
        $file->move($location, $filename);
        // $filename =  Storage::disk('public')->put("staff", $request['image']);

        $request_data = $request;
        // $request_data['image'] = 'storage/' . $filename;
        $request_data['image'] = $filename;

        return $request_data;
    }
}