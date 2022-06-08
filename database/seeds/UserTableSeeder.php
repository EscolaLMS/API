<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserTableSeeder extends Seeder
{
    public function run()
    {
        $admin = Role::findOrCreate('admin', 'api');
        $tutor = Role::findOrCreate('tutor', 'api');
        $student = Role::findOrCreate('student', 'api');

        $student = User::firstOrCreate([
                'first_name' => 'Osman',
                'last_name' => 'Kanu',
                'email' => 'student@escolalms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
        $student->guard_name = 'api';
        $student->assignRole('student');

        $admin = User::firstOrCreate([
                'first_name' => 'Admin',
                'last_name' => 'A',
                'email' => 'admin@escolalms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);

        $admin->guard_name = 'api';
        $admin->assignRole('admin');

        $tutor = User::firstOrCreate([
                'first_name' => 'Angela',
                'last_name' => 'Yu',
                'email' => 'tutor@escolalms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);

        $tutor->guard_name = 'api';
        $tutor->assignRole('tutor');


        $tutors = User::factory()->count(10)->create();

        foreach ($tutors as $tutor) {
            $tutor->guard_name = 'api';
            $tutor->assignRole('tutor');
        }

        $students = User::factory()->count(10)->create();

        foreach ($students as $student) {
            $student->guard_name = 'api';
            $student->assignRole('student');
        }
    }
}
