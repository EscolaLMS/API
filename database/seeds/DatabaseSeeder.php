<?php

namespace Database\Seeders;

use EscolaLms\Core\Seeders\RoleTableSeeder;

use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
use Illuminate\Database\Seeder;

use EscolaLms\Files\Seeders\PermissionTableSeeder as FilePermissionTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // first populate roles & users
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(FilePermissionTableSeeder::class);

        // then populate content
        $this->call(CategoriesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(CoursesSeeder::class);
    }
}
