<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\FileResource;
use App\Repositories\FileRepository;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

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

        $response = new Response($file->file);

        $disposition = $response->headers->makeDisposition(
            ResponseHeaderBag::DISPOSITION_ATTACHMENT,
            $file->file
        );

        return $response->headers->set('Content-Disposition', $disposition);
    }
}