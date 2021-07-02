<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function register(Request $request)
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

        $user = $this->repository->create($request->only(['name', 'family', 'phone', 'national_code', 'password']));

        if ($user) {
            return (new UserResource($user))->additional([
                'token' => $user->createToken('register')->plainTextToken,
            ]);
        } else {
            return response(['status' => false], 422);
        }
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'national_code' => 'required|min:10|max:10',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if (Auth::attempt($request->toArray())) {
            return (new UserResource(auth()->user()))->additional([
                'token' => auth()->user()->createToken('login')->plainTextToken,
            ]);
        } else {
            $response = ['message' => 'نام کاربری یا رمزعبور اشتباه است', 'status' => false];
            return response($response, 422);
        }
    }


    public function logout()
    {
        $delete =  auth()->user()->tokens()->delete();

        if ($delete) {
            return response(['status' => true], 200);
        }

        return response(['status' => false], 422);
    }
}