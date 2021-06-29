<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\DatabaseSeeder;
use EscolaLms\HeadlessH5P\Database\Seeders\ContentLibrarySeeder;
use Database\Seeders\PostCoursesSeeder;

class FullDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(DatabaseSeeder::class);
        $this->call(ContentLibrarySeeder::class);
        $this->call(PostCoursesSeeder::class);
    }
}
