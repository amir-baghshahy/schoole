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

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $user->id . 'id',
            'national_code' => 'required|min:10|max:10|unique:users,national_code,' . $user->id . 'id',
            'password' => 'required|string|min:8|confirmed',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }


        $update = $this->repository->update($user, $request->only(['name', 'family', 'phone', 'national_code', 'password']));

        if ($update) {
            $user = $this->repository->finduser($user->id);
            return (new UserResource($user))->additional([
                'status' => true
            ]);
        }
        return response(['status' => false]);
    }
}