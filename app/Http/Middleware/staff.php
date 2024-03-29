<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class staff
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role == 1 || auth()->user()->role == 0) {
            return $next($request);
        }
        return response("Forbidden", 403);
    }
}