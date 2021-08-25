<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\StaffResource;
use App\Repositories\StaffRepository;
use Illuminate\Support\Facades\Validator;

class StaffController extends Controller
{

    protected $repository;

    public function __construct(StaffRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new StaffResource($this->repository->all()->active()->get());
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        if ($user->role != 1) {
            return response("Forbidden", 403);
        }

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
        ]);


        $result = $this->repository->update($request->id,  $request->except(['id']));

        if ($result) {
            return response(['status' => true], 200);
        } else {
            return response(['status' => false], 422);
        }
    }
}