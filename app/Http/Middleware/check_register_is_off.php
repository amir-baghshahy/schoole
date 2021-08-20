<?php

namespace App\Http\Middleware;

use App\Models\Setting;
use Closure;
use Illuminate\Http\Request;

class check_register_is_off
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

        if ($setting->register_mode == 0) {
            return $next($request);
        } elseif ($setting->register_mode == 1) {
            return response(['message' => 'در حال حاضر این بخش  توسط مدیر از دسترس  خارج شده است', 'code' => '503'], 503);
        }
    }
}