<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Rules\Nationalcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Validator;

class AdminAccountController extends Controller
{
    protected $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }

    public function find($id)
    {
        $account = $this->repository->find($id);
        return new AccountResource($account);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required|string|max:255',
            'phone' => 'required|max:11|min:11|unique:users,phone,' . $request->id . 'id',
            'home_phone' => 'required|unique:users,home_phone,' . $request->id . 'id',
            'password' => 'required|string|min:8|confirmed',
            'birthday_city' => 'required',
            'place_issue' => 'required',
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'national_code' => ['required', new Nationalcode, 'unique:accounts,national_code,' . $request->id],
            'birthday' => 'required',
            'grade' => 'required|max:255',
            'major_name' => 'required|string|max:255',
            'address' => 'required|string',
            'dad_name' => 'required|string|max:255',
            'degree_dad' => 'required|string',
            'dad_phone' => 'nullable|unique:accounts,dad_phone,' . $request->id,
            'dad_work_address' => 'nullable',
            'dad_is_dead' => 'required|string|max:255',
            'mom_name' => 'required|string|max:255',
            'mom_family' => 'required|string|max:255',
            'degree_mom' => 'required|string|max:255',
            'mom_phone' => 'nullable|unique:accounts,mom_phone,' . $request->id,
            'mom_work_address' => 'nullable',
            'mom_is_dead' => 'required|string',
            'relatives_phone' => 'required|digits:11|unique:accounts,relatives_phone,' . $request->id,
            'relatives_name' => 'required|string|max:255',
        ], [
            'phone.unique' => 'شماره قبلا ثبت شده است ',
            'home_phone.unique' => 'شماره منزل قبلا ثبت شده است ',
            'dad_phone.unique' => 'شماره پدر قبلا ثبت شده است ',
            'national_code.unique' => 'کد ملی قبلا ثبت شده است',
            'mom_phone.unique' => 'شماره مادر قبلا ثبت شده است ',
            'relatives_phone.unique' => 'شماره اقوام  قبلا ثبت شده است'
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user = User::findOrFail($request->id);
        $user->update($request->only(['phone', 'password']));
        $account = $this->repository->find($user->id);
        $update_account = $this->repository->update($account, $request->toArray());

        if ($update_account) {
            return (new AccountResource($account))->additional([
                'status' => true,
                'user' => $user
            ]);
        }
        return response(['status' => false]);
    }
}