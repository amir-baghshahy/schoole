<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class AdminSettingController extends Controller
{

    public function get_seeting()
    {
        return  Setting::where('id', 1);
    }


    public function switch_website()
    {
        $setting = $this->get_seeting();

        if ($setting->first()->web_mode == 0) {
            return  $setting->update([
                'web_mode' => 1
            ]);
        } elseif ($setting->first()->web_mode == 1) {
            return  $setting->update([
                'web_mode' => 0
            ]);
        }
    }


    public function switch_register()
    {
        $setting = $this->get_seeting();

        if ($setting->first()->register_mode == 0) {
            return  $setting->update([
                'register_mode' => 1
            ]);
        } elseif ($setting->first()->register_mode == 1) {
            return  $setting->update([
                'register_mode' => 0
            ]);
        }
    }
}