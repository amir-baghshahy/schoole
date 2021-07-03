<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\AccountResource;
use App\Repositories\AccountRepository;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{

    protected $repository;

    public function __construct(AccountRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index()
    {
        return new AccountResource($this->repository->find(auth()->user()->id));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|string|max:255',
                'family' => 'required|string|max:255',
                'national_code' => 'required|min:10|max:10|unique:accounts,national_code,' . $user->id,
                'birthday' => 'required|string',
                'grade' => 'required|max:255',
                'major_name' => 'required|string|max:255',
                'address' => 'required|string',
                'dad_name' => 'required|string|max:255',
                'dad_phone' => 'required|digits:11|regex:/(09)[0-9]{9}/|unique:accounts,dad_phone,' . $user->id,
                'dad_work_address' => 'required|string',
                'dad_is_dead' => 'required|string|max:255',
                'mom_phone' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:accounts,mom_phone,' . $user->id,
                'mom_work_address' => 'required|string|max:255',
                'mom_is_dead' => 'required|string',
                'relatives_phone' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:accounts,relatives_phone,' . $user->id,
                'relatives_name' => 'required|string|max:255',
            ],
            [

                'dad_phone.unique' => 'شماره پدر قبلا ثبت شده است ',
                'national_code.unique' => 'کد ملی قبلا ثبت شده است',
                'mom_phone.unique' => 'شماره مادر قبلا ثبت شده است ',
                'relatives_phone.unique' => 'شماره اقوام  قبلا ثبت شده است'
            ]
        );


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }


        $account = $this->repository->find($user->id);
        $update = $this->repository->update($account, $request->toArray());


        if ($update) {
            return (new AccountResource($account))->additional([
                'status' => true
            ]);
        }
        return response(['status' => false]);
    }
}