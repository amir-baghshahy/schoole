<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Rules\Nationalcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Staff;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminUserController extends Controller
{
    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }


    public function index(Request $request, $status)
    {
        if ($status == "teachers") {
            return new UserResource($this->repository->get_teachars());
        } elseif ($status == "students") {
            return new UserResource($this->repository->get_students());
        } elseif ($status == "not-accepted") {
            return new UserResource($this->repository->get_not_accepted());
        } elseif ($status == "waiting-accepted") {
            return new UserResource($this->repository->get_wait_accepted());
        } elseif ($status == "incomplete-information") {
            return new UserResource($this->repository->get_incomplete_info());
        } elseif ($status == "archive") {
            return new UserResource($this->repository->get_archives());
        } elseif ($status == "all") {
            return new UserResource($this->repository->get_all($request));
        } else {
            return response(["message" => "not found", "code" => "404"], 404);
        }
    }

    public function create(Request $request)
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
            'phone.unique' => 'شماره تلفن قبلا ثبت شده است ',
            'dad_phone.unique' => 'شماره پدر قبلا ثبت شده است ',
            'national_code.unique' => 'کد ملی قبلا ثبت شده است',
            'mom_phone.unique' => 'شماره مادر قبلا ثبت شده است ',
            'relatives_phone.unique' => 'شماره  اقوام قبلا ثبت شده است'
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $user_request_create = $request;
        $user_request_create['status'] = 'accepted';
        $user_request_create['status_cause'] = 'مشخصات فردی شما مورد تأیید بوده و احراز هویت انجام شده است. بنابراین تنها برخی از مشخصات خود را می‌توانید ویرایش نمایید.';
        $user = $this->repository->create($user_request_create->only(['phone', 'password', 'status', 'status_cause']));
        $requesdaata = $request;
        $requesdaata['user_id'] = $user->id;
        Account::create($request->toArray());

        if ($user) {
            return response(['status' => true], 200);
        } else {
            return response(['status' => false], 422);
        }
    }




    public function change_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|max:255',
            'status' => 'required',
            'status_cause' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $request->toArray();

        if ($request->status == 'accepted') {
            $request_data['status_cause'] = 'مشخصات فردی شما مورد تأیید بوده و احراز هویت انجام شده است. بنابراین تنها برخی از مشخصات خود را می‌توانید ویرایش نمایید.';
        }

        $user = $this->repository->finduser($request->user_id);
        $update = $this->repository->update($user, $request->only(['status', 'status_cause']));

        if ($update) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function delete($id)
    {
        $delete  = $this->repository->delete($id);

        if ($delete) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function archive($id)
    {
        $user =  $this->repository->finduser($id);
        $update =  $this->repository->update($user, ['archive' => true]);

        if ($update) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function grade_up()
    {
        $update = User::with(['account' => function ($q) {
            return  $q->where('grade', '=', '1')->Orwhere('grade', '=', '2')->update(['grade' => DB::raw('grade+1')]);
        }])->where([['role', 2], ['archive', false], ['status', 'accepted']])->get();


        $update_archive = User::whereHas('account', function ($query) {
            return $query->where('grade', 3);
        })->where([['role', '=', 2], ['archive', '=', false], ['status', 'accepted']]);

        $result_archive =  $update_archive->update(['archive' => true]);

        if ($update && $result_archive) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }
}