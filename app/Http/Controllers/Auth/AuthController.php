<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Account;
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
            'phone' => 'required|digits:11|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = $this->repository->create($request->only(['phone', 'password']));
        Account::create(['user_id' => $user->id]);

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
            'phone' => 'required|digits:11',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if (Auth::attempt($request->toArray())) {
            $check_archiv = $this->check_archive($request->phone);
            if($check_archiv != null){
                var_dump($check_archiv);
                  return (new UserResource(auth()->user()))->additional([
                'token' => auth()->user()->createToken('login')->plainTextToken,
            ]);
         }else{
               return response(["message"=>"شما توسط مدیر مسدود شده اید "], 401);   
           }
 
        } else {
            $response = ['message' => 'شماره همراه  یا رمزعبور اشتباه است', 'status' => false];
            return response($response, 422);
        }
    }
    
    public function check_archive($phone)
    {
        return User::where([['phone','=',$phone],['archive','=',false]])->get();
    }


    public function logout()
    {
        $delete =  auth()->user()->currentAccessToken()->delete();

        if ($delete) {
            return response(['status' => true], 200);
        }

        return response(['status' => false], 422);
    }
}
