<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $student = User::create([
                'first_name' => 'Osman',
                'last_name' => 'Kanu',
                'email' => 'student@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);

        $admin = User::create([
                'first_name' => 'Admin',
                'last_name' => 'A',
                'email' => 'admin@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);

        $instructor_user = User::create([
                'first_name' => 'Angela',
                'last_name' => 'Yu',
                'email' => 'instructor@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
    }
}
