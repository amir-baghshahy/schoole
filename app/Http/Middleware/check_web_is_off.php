<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Setting;
use Illuminate\Http\Request;

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
        $setting = Setting::find(['id' => 1])->first();

        if ($setting == null) {
            $setting = Setting::create(['web_mode' => 0, 'register_mode' => 0]);
        };

        if ($setting->web_mode == 0) {
            return $next($request);
        } elseif ($setting->web_mode == 1) {
            return response(['message' => 'در حال حاضر وبسایت در دسترس نمی باشد', 'code' => '503'], 503);
        }
    }
}