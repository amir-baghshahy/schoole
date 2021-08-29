<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class check_web_is_off
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
        $setting = Setting::find(1)->first();

        if ($setting->web_mode == 0) {
            return $next($request);
        }elseif ($setting->web_mode == 1) {
                       dd(auth()->user()->role);
            if(Auth::check())
            {
                if(auth()->user()->role ==0){
                     return $next($request);
                }else{
                      return response(['message' => 'در حال حاضر وبسایت در دسترس نمی باشد', 'code' => '503'], 503);
                }
            }else{
                  return response(['message' => 'در حال حاضر وبسایت در دسترس نمی باشد', 'code' => '503'], 503);
            }

        }
    }
}
