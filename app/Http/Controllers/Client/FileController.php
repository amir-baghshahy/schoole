<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $repository;

    public function __construct(FileRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getall()
    {
        return new FileResource($this->repository->index());
    }


    public function find($id)
    {
        $file =  $this->repository->find($id);

        return response()->download(public_path() . "/" . $file->file);
    }
}