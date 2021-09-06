<?php

namespace Database\Seeders;

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

        $user = User::create([
            'phone' => '09140912196',
            'role' => '0',
            'status' => '',
            'status_cause' => '',
            'super_user' => true,
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
        ]);

        Staff::create(['user_id' => $user->id, 'name' => 'امیر', 'family' => 'باغشاهی', 'rolename' => 'مدیر', 'teaching_experience' => 0, 'major' => 'کامپیوتر', "birthday" => "1381-12-25", 'status' => 0]);
    }
}