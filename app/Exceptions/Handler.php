<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json(['message' => "method not allow", 'code' => 405], 405);
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return response()->json(['message' => "404", 'code' => 404], 404);
        });

        $this->renderable(function (TooManyRequestsHttpException $e) {
            return response()->json(['message' => "to many request", 'code' => 429], 429);
        });

        $this->renderable(function (AuthenticationException $e) {
            return response()->json(['message' => "Unauthenticated", 'code' => 401], 401);
        });
    }
}