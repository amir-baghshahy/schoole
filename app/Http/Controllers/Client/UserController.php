<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new UserResource($this->repository->finduser(auth()->user()->id));
    }

    public function update(Request $request)
    {

        $user = auth()->user();

        if ($request->has('password')) {
            $validator = Validator::make(
                $request->all(),
                [
                    'phone' => 'required|max:11|min:11|unique:users,phone,' . $user->id,
                    'password' => 'required|string|min:8|confirmed',
                ],
                [
                    'phone.unique' => 'شماره قبلا ثبت شده است ',
                ]
            );

            if ($validator->fails()) {
                return response(['message' => $validator->errors()->first(), 'status' => false], 422);
            }

            $update = $this->repository->update($user, $request->only(['phone', 'password']));

            if ($update) {
                $user = $this->repository->finduser($user->id);
                return (new UserResource($user))->additional([
                    'status' => true
                ]);
            }
            return response(['status' => false]);
        } else {

            $validator = Validator::make(
                $request->all(),
                [
                    'phone' => 'required|max:11|min:11|unique:users,phone,' . $user->id
                ],
                [
                    'phone.unique' => 'شماره قبلا ثبت شده است ',
                ]
            );

            if ($validator->fails()) {
                return response(['message' => $validator->errors()->first(), 'status' => false], 422);
            }


            $update = $this->repository->update($user, $request->only(['phone']));

            if ($update) {
                $user = $this->repository->finduser($user->id);
                return (new UserResource($user))->additional([
                    'status' => true
                ]);
            }

            return response(['status' => false]);
        }
    }
}