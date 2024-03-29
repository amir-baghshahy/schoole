<?php

namespace App\Http\Controllers\Admin;

use App\Models\Account;
use App\Rules\Nationalcode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
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
        $result = null;

        switch ($status) {
            case 'students':
                $result = $this->repository->get_students($request);
                break;
            case 'not-accepted':
                $result = $this->repository->get_not_accepted($request);
                break;
            case 'waiting-accepted':
                $result = $this->repository->get_wait_accepted($request);
                break;
            case 'incomplete-information':
                $result = $this->repository->get_incomplete_info($request);
                break;
            case 'archive':
                $result = $this->repository->get_archives($request);
                break;
            case 'all':
                $result = $this->repository->get_all($request);
                break;
            default:
                return response(['message' => 'not found', 'code' => '404'], 404);
                break;
        }

        return new UserResource($result);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'family' => 'required|string|max:255',
            'phone' => 'required|unique:users',
            'national_code' => ['required', new Nationalcode, 'unique:accounts'],
            'birthday' => 'required',
            'birthday_city' => 'required',
            'place_issue' => 'required',
            'home_phone' => 'required|unique:accounts',
            'grade' => 'required|max:255',
            'major_name' => 'required|string|max:255',
            'address' => 'required|string',
            'dad_name' => 'required|string|max:255',
            'degree_dad' => 'required|string',
            'dad_phone' => 'nullable|unique:accounts',
            'dad_work_address' => 'nullable',
            'dad_is_dead' => 'required',
            'mom_name' => 'required|string|max:255',
            'mom_family' => 'required|string|max:255',
            'degree_mom' => 'required|string|max:255',
            'mom_phone' => 'nullable|unique:accounts',
            'mom_work_address' => 'nullable',
            'mom_is_dead' => 'required',
            'relatives_phone' => 'required|unique:accounts',
            'relatives_name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
            'count_sister' => 'required|string',
            'count_brother' => 'required|string',
            'count_family' => 'required|string',
            'several_child_count' => 'required|string',
            'nationality' => 'required|string',
            'faith' => 'required|string',
            'religion' => 'required|string',
            'dad_work' => 'nullable',
            'mom_work' => 'nullable',
            'last_mark' => 'required|string',
            'last_schoole' => 'required|string',
            'disease_background' => 'nullable',
            'separation_family_select' => 'nullable',
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

        $create_user_request = $request;
        $create_user_request['status'] = 'accepted';
        $create_user_request['status_cause'] = 'مشخصات فردی شما مورد تأیید بوده و احراز هویت انجام شده است. بنابراین تنها برخی از مشخصات خود را می‌توانید ویرایش نمایید.';

        $user = $this->repository->create($create_user_request->only(['phone', 'password', 'status', 'status_cause']));

        $request_data = $request->except(['phone', 'password', 'status', 'status_cause']);
        $request_data['user_id'] = $user->id;

        if (Account::create($request_data)) {
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
            'status_cause' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        $request_data = $request->only(['status', 'status_cause']);

        $user = $this->repository->finduser($request->user_id);


        if ($request->status == 'accepted' && $request->status_cause == "") {

            $request_data['status_cause'] = 'مشخصات فردی شما مورد تأیید بوده و احراز هویت انجام شده است. بنابراین تنها برخی از مشخصات خود را می‌توانید ویرایش نمایید.';
        }


        if ($request->status == 'not-accepted' && $request->status_cause == "") {
            $request_data['status_cause'] = 'احراز هویت شما توسط مدیر رد شد';
        }

        if ($request->status == 'waiting-accepted' && $request->status_cause == "") {
            $request_data['status_cause'] = 'منتظر  برای تایید هویت شما توسط مدیر';
        }

        if ($request->status == 'incomplete-information' && $request->status_cause == "") {
            $request_data['status_cause'] = ' لطفا احراز هویت خود را تکمیل کنید';
        }

        $update = $this->repository->update($user, $request_data);

        if ($update) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function delete($id)
    {
        $user = $this->repository->finduser($id);

        if ($user->status = 'accepted') {
            if (!auth()->user()->super_user) {
                return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
            }
            $delete  = $this->repository->delete($id);
        } else {
            $delete  = $this->repository->delete($id);
        }

        if ($delete) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }


    public function archive($id)
    {
        $user =  $this->repository->finduser($id);

        if ($user->status == 'accepted') {
            if (!auth()->user()->super_user) {
                return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
            } else {
                $update =  $this->repository->update($user, ['archive' => true]);
            }
        } else {
            $update =  $this->repository->update($user, ['archive' => true]);
        }

        if ($update) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function grade_up()
    {
        if (auth()->user()->super_user) {


            $update_archive = User::whereHas('account', function ($query) {
                return $query->where('grade', 3);
            })->where([['role', '=', 2], ['archive', '=', false], ['status', 'accepted']]);

            $result_archive =  $update_archive->update(['archive' => true]);

            $update = User::with(['account' => function ($q) {
                return  $q->where('grade', '=', '1')->Orwhere('grade', '=', '2')->update(['grade' => DB::raw('grade+1')]);
            }])->where([['role', 2], ['status', 'accepted']])->archive(false)->get();

            if ($update && $result_archive) {
                return response(['status' => true]);
            }
            return response(['status' => false]);
        }

        return response(['message' => 'شما به این بخش دسترسی ندارید'], 403);
    }
}