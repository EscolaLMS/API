<?php

namespace Database\Seeders;

use EscolaLms\Core\Seeders\RoleTableSeeder;

use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesPermissionSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
use EscolaLms\Files\Database\Seeders\PermissionTableSeeder as FilePermissionTableSeeder;
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
        // first populate roles & permissions
        $this->call(RoleTableSeeder::class);
        $this->call(FilePermissionTableSeeder::class);
        $this->call(CoursesPermissionSeeder::class);

        // create users
        $this->call(UserTableSeeder::class);

        // then populate content
        $this->call(CategoriesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(CoursesSeeder::class);
    }
}
