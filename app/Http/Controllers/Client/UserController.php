<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
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
        $rules = array(
            'phone' => 'required|max:11|min:11|unique:users,phone,' . auth()->user()->id
        );

        if ($request->has('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $validator = Validator::make(
            $request->all(),
            $rules,
            [
                'phone.unique' => 'شماره قبلا ثبت شده است ',
            ]
        );

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->update(auth()->user(), $request->toArray())) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }
}