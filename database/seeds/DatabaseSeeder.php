<?php

namespace Database\Seeders;

use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Categories\Database\Seeders\CategoriesPermissionSeeder;

use EscolaLms\Core\Seeders\RoleTableSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesPermissionSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Payments\Database\Seeders\PaymentsPermissionsSeeder;
use EscolaLms\Payments\Database\Seeders\PaymentsSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
use EscolaLms\Files\Database\Seeders\PermissionTableSeeder as FilePermissionTableSeeder;

use EscolaLms\Pages\Database\Seeders\DatabaseSeeder as PagesDatabaseSeeder;
use EscolaLms\Pages\Database\Seeders\PermissionTableSeeder as PagesPermissionTableSeeder;

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
        $this->call(PaymentsPermissionsSeeder::class);
        $this->call(CategoriesPermissionSeeder::class);
        $this->call(PagesPermissionTableSeeder::class);

        // create users
        $this->call(UserTableSeeder::class);

        // then populate content
        $this->call(CategoriesSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(CoursesSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(PagesDatabaseSeeder::class);
    }
}
