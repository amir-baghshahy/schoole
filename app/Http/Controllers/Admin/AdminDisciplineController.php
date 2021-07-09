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

        $result = $this->repository->create($request->toArray());

        if ($result) {
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

        $discipline = $this->repository->find($request->id);

        $result = $this->repository->update($discipline, $request->toArray());


        if ($result) {
            return (new DisciplineResource($discipline))->additional([
                "status" => $result
            ]);
        }
        return response(['status' => false]);
    }

    public function delete($id)
    {
        $delete = $this->repository->delete($id);

        if ($delete) {
            return response(['status' => true]);
        } else {
            return response(['status' => false]);
        }
    }
}