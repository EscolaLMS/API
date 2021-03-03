<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(CategoryTableSeeder::class);
        $this->call(ConfigTableSeeder::class);
        $this->call(BlogTableSeeder::class);
        $this->call(CourseTableSeeder::class);
        $this->call(TagTableSeeder::class);
        $this->call(StudentWithAllCourses::class);
        // $this->call(H5PLibrarySeeder::class); dont seed this, that to much do it manually
    }
}
