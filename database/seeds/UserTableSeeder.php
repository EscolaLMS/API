<?php

namespace Database\Seeders;

use App\Enum\UserRole;
use App\Models\Instructor;
use App\Models\User;
use App\Repositories\Contracts\InstructorRepositoryContract;
use Carbon\Carbon;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class UserTableSeeder extends Seeder
{
    private InstructorRepositoryContract $instructorRepository;

    /**
     * UserTableSeeder constructor.
     * @param InstructorRepositoryContract $instructorRepository
     */
    public function __construct(InstructorRepositoryContract $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function run()
    {
        $faker = Factory::create();
        $is_exist = User::all();
        if (!$is_exist->count()) {
            $student = User::create([
                'first_name' => 'Osman',
                'last_name' => 'Kanu',
                'email' => 'student@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
            $student->assignRole(UserRole::STUDENT);

            $admin = User::create([
                'first_name' => 'Admin',
                'last_name' => 'A',
                'email' => 'admin@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
            $admin->assignRole(UserRole::ADMIN);
            $this->instructorRepository->createUsingUser($admin);

            //import instructors
            $instructor_user = User::create([
                'first_name' => 'Angela',
                'last_name' => 'Yu',
                'email' => 'instructor@escola-lms.com',
                'password' => bcrypt('secret'),
                'is_active' => 1,
                'email_verified_at' => Carbon::now(),
            ]);
            $instructor_user->assignRole(UserRole::STUDENT);

            $path = Storage::putFile('instructors', database_path('seeds/multimedia/image.jpg'), 'public');

            Instructor::create([
                'first_name' => 'Angela',
                'last_name' => 'Yu',
                'user_id' => $instructor_user->id,
                'instructor_slug' => 'angela-yu',
                'contact_email' => 'instructor@escola-lms.com',
                'telephone' => '+61 (800) 123-54323',
                'biography' => $faker->text(100),
                'mobile' => '+61 800-1233-8766',
                'paypal_id' => 'instructor@escola-lms.com',
                'instructor_image' => "storage/$path",
            ]);
            $instructor_user->assignRole(UserRole::INSTRUCTOR);
        }
    }
}
