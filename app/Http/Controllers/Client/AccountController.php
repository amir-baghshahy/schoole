<?php

namespace App\Http\Controllers\Client;

use App\Models\User;
use App\Rules\Nationalcode;
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
        $status_update = null;


        if ($user->status == 'accepted' && $user->role == 2) {
            $status_update = false;
            $validator = Validator::make(
                $request->all(),
                [
                    'dad_phone' => 'nullable|unique:accounts,dad_phone,' . $user->id,
                    'dad_work_address' => 'nullable',
                    'dad_is_dead' => 'required',
                    'mom_phone' => 'nullable|unique:accounts,mom_phone,' . $user->id,
                    'mom_work_address' => 'nullable',
                    'mom_is_dead' => 'required',
                ],
                [

                    'dad_phone.unique' => 'شماره پدر قبلا ثبت شده است ',
                    'mom_phone.unique' => 'شماره مادر قبلا ثبت شده است ',
                    'relatives_phone.unique' => 'شماره اقوام  قبلا ثبت شده است'
                ]
            );
        } else {
            $status_update = true;
            $validator = Validator::make(
                $request->all(),
                [
                    'name' => 'required|string|max:255',
                    'family' => 'required|string|max:255',
                    'national_code' => ['required', new Nationalcode, 'unique:accounts,national_code,' . $user->id],
                    'birthday' => 'required',
                    'birthday_city' => 'required',
                    'place_issue' => 'required',
                    'home_phone' => 'required|unique:accounts,home_phone,' . $user->id . 'id',
                    'grade' => 'required|max:255',
                    'major_name' => 'required|string|max:255',
                    'address' => 'required|string',
                    'dad_name' => 'required|string|max:255',
                    'degree_dad' => 'required|string',
                    'dad_phone' => 'nullable|unique:accounts,dad_phone,' . $user->id,
                    'dad_work_address' => 'nullable',
                    'dad_is_dead' => 'required',
                    'mom_name' => 'required|string|max:255',
                    'mom_family' => 'required|string|max:255',
                    'degree_mom' => 'required|string|max:255',
                    'mom_phone' => 'nullable|unique:accounts,mom_phone,' . $user->id,
                    'mom_work_address' => 'nullable',
                    'mom_is_dead' => 'required',
                    'relatives_phone' => 'required|regex:/(09)[0-9]{9}/|digits:11|unique:accounts,relatives_phone,' . $user->id,
                    'relatives_name' => 'required|string|max:255',
                ],
                [

                    'dad_phone.unique' => 'شماره پدر قبلا ثبت شده است ',
                    'national_code.unique' => 'کد ملی قبلا ثبت شده است',
                    'home_phone.unique' => 'شماره منزل قبلا ثبت شده است ',
                    'mom_phone.unique' => 'شماره مادر قبلا ثبت شده است ',
                    'relatives_phone.unique' => 'شماره اقوام  قبلا ثبت شده است'
                ]
            );
        }


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $account = $this->repository->find($user->id);

        if ($status_update == true) {
            $update = $this->repository->update($account, $request->toArray());
            $user_update = User::find($user->id);
            $user_update->update(['status' => 'waiting-accepted', 'status_cause' => 'منتظر برای تایید ادمین']);
        } elseif ($status_update == false) {
            $update = $this->repository->update($account, $request->only(['dad_phone', 'dad_work_address', 'dad_is_dead', 'mom_phone', 'mom_work_address', 'mom_is_dead']));
        }

        if ($update) {
            return (new AccountResource($account))->additional([
                'status' => true
            ]);
        }
        return response(['status' => false]);
    }
}