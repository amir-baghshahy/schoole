<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DisciplineResource;
use Illuminate\Support\Facades\Validator;
use App\Repositories\DisciplineRepository;

class AdminDisciplineController extends Controller
{
    protected $repository;

    public function __construct(DisciplineRepository $repository)
    {
        $this->repository = $repository;
    }

    public function get_all()
    {
        return new DisciplineResource($this->repository->getall());
    }

    public function get_by_user($id)
    {
        return new DisciplineResource($this->repository->findbyuser($id));
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cause' => 'required|string',
            "user_id" => "required",
            'time' => 'required',
            'date' => 'required',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->create($request->toArray())) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cause' => 'required|string',
            "id" => "required",
            'time' => 'required',
            'date' => 'required',
        ]);


        if ($validator->fails()) {
            return response(['message' => $validator->errors()->first(), 'status' => false], 422);
        }

        if ($this->repository->update($request->toArray())) {
            return response(['status' => true]);
        }
        return response(['status' => false]);
    }

    public function delete($id)
    {
        if ($this->repository->delete($id)) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }
}