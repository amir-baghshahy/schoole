<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new UserResource($this->repository->getall());
    }

    public function get_teachers()
    {
        return new UserResource($this->repository->get_teachars());
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'phone' => 'required|max:11|min:11|unique:users',
            'national_code' => 'required|min:10|max:10|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
            'national_code.unique' => 'کد ملی قبلا ثبت شده است'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->create($request->toArray());

        if ($user) {
            return (new UserResource($user));
        } else {
            return response(['status' => false], 422);
        }
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'role' => 'required',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $request->id . 'id',
            'national_code' => 'required|min:10|max:10|unique:users,national_code,' . $request->id . 'id',
            'password' => 'required|string|min:8|confirmed',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->finduser($request->id);

        $update = $this->repository->update($user, $request->only(['name', 'family', 'phone', 'national_code', 'password', 'role']));

        if ($update) {
            $user = $this->repository->finduser($user->id);
            return (new UserResource($user))->additional([
                'status' => true
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
}