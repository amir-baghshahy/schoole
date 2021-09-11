<?php

namespace Database\Seeders;

use App\Models\About;
use App\Models\User;
use App\Models\Setting;
use App\Models\Staff;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Setting::create([
            'web_mode' => 0,
            'register_mode' => 0
        ]);

        About::create(["text" => ""]);

        $user = User::create([
            'phone' => '09140912196',
            'role' => '0',
            'status' => '',
            'status_cause' => '',
            'super_user' => true,
            'password' => '12345678',
        ]);

        $user1 = User::create([
            'phone' => '09224048766',
            'role' => '0',
            'status' => '',
            'status_cause' => '',
            'super_user' => false,
            'password' => '12345678',
        ]);

        $user2 = User::create([
            'phone' => '09013137962',
            'role' => '0',
            'status' => '',
            'status_cause' => '',
            'super_user' => false,
            'password' => '12345678',
        ]);

        Staff::create(['user_id' => $user2->id, 'name' => 'qqqqq', 'family' => 'bbbbbb', 'rolename' => 'مدیر', 'teaching_experience' => 0, 'major' => 'کامپیوتر', "birthday" => "1381-12-25", 'status' => 0, 'degree' => 'لیسانس']);

        Staff::create(['user_id' => $user1->id, 'name' => 'jhgjgh', 'family' => 'ghjgh', 'rolename' => 'مدیر', 'teaching_experience' => 0, 'major' => 'کامپیوتر', "birthday" => "1381-12-25", 'status' => 0, 'degree' => 'لیسانس']);

        Staff::create(['user_id' => $user->id, 'name' => 'امیر', 'family' => 'باغشاهی', 'rolename' => 'مدیر', 'teaching_experience' => 0, 'major' => 'کامپیوتر', "birthday" => "1381-12-25", 'status' => 0, 'degree' => 'لیسانس']);
    }
}