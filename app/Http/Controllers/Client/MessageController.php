<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\MessageRepository;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{

    protected $repository;

    public function __construct(MessageRepository $repository)
    {
        $this->repository = $repository;
    }


    public function create(Request $request)
    {
        if (auth()->user()->status == 'accepted') {

            $validator = Validator::make($request->all(), [
                'title' => 'required|string|max:255',
                'message' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response(['message' => $validator->errors()->first(), 'status' => false], 422);
            }

            $request_data = $request;
            $request_data['user_id'] = auth()->user()->id;

            $result = $this->repository->create($request_data->toArray());

            if ($result) {
                return response(['status' => true]);
            } else {
                return response(['status' => false]);
            }
        } else {
            return response('Forbidden', 403);
        }
    }
}