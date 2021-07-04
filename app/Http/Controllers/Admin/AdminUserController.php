<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
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


    public function index($status)
    {
        if ($status == "teachers") {
            return new UserResource($this->repository->get_teachars());
        } elseif ($status == "students") {
            return new UserResource($this->repository->get_students());
        } elseif ($status == "not-accepted") {
            return new UserResource($this->repository->get_not_accepted());
        } elseif ($status == "wait-accepted") {
            return new UserResource($this->repository->get_wait_accepted());
        } elseif ($status == "incomplete-information") {
            return new UserResource($this->repository->get_incomplete_info());
        }elseif ($status == "all") {
            return new UserResource($this->repository->get_all());
        }
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|max:11|min:11|unique:users',
            'role' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->create($request->toArray());
        if ($user->role != 1 && $user->role != 0) {
            Account::create(['user_id' => $user->id]);
        }

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
            'role' => 'required',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $request->id . 'id',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->finduser($request->id);

        $update = $this->repository->update($user, $request->only(['phone', 'password', 'role']));

        if ($update) {
            $user = $this->repository->finduser($user->id);
            return (new UserResource($user))->additional([
                'status' => true
            ]);
        }
        return response(['status' => false]);
    }


    public function change_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|max:255',
            'status' => 'required',
            'status_cause' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->finduser($request->user_id);
        $update = $this->repository->update($user, $request->only(['status', 'status_cause']));

        if ($update) {
            return response(['status' => true]);
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