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
use App\Models\Setting;

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
        

        if (Auth::attempt($request->only(['phone', 'password']))) {
            return (new UserResource($user))->additional([
                'token' => auth()->user()->createToken('register')->plainTextToken,
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
            
            $setting = Setting::find(1)->first();
           if(count($check_archiv) == 1){
                 if ($setting->web_mode == 1) {
                      if(auth()->user()->role==0){
                          session('user_role',auth()->user()->role);
                         return (new UserResource(auth()->user()))->additional([
                                'token' => auth()->user()->createToken('login')->plainTextToken,
                            ]);
                       }else{
                           return response(['message' => 'در حال حاضر وبسایت در دسترس نمی باشد', 'code' => '503'], 503);
                      }
                 }else{
                       session('user_role',auth()->user()->role);
                      return (new UserResource(auth()->user()))->additional([
                            'token' => auth()->user()->createToken('login')->plainTextToken,
                     ]);
                 }
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
            unset(session('user_role'));
            return response(['status' => true], 200);
        }

        return response(['status' => false], 422);
    }
}
