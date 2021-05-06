<?php

namespace Database\Seeders;

use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
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
        $this->call(UserTableSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(CoursesSeeder::class);
    }
}
