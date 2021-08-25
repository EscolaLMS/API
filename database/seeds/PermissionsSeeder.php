<?php

namespace Database\Seeders;

use EscolaLms\Core\Seeders\RoleTableSeeder;
use EscolaLms\Auth\Database\Seeders\AuthPermissionSeeder;
use EscolaLms\Cart\Database\Seeders\CartPermissionSeeder;
use EscolaLms\Categories\Database\Seeders\CategoriesPermissionSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesPermissionSeeder;
use EscolaLms\Payments\Database\Seeders\PaymentsPermissionsSeeder;
use EscolaLms\Files\Database\Seeders\PermissionTableSeeder as FilePermissionTableSeeder;
use EscolaLms\Pages\Database\Seeders\PermissionTableSeeder as PagesPermissionTableSeeder;
use EscolaLms\Scorm\Database\Seeders\PermissionTableSeeder as ScormPermissionTableSeeder;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
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
        $this->call(AuthPermissionSeeder::class);
        $this->call(CartPermissionSeeder::class);
        $this->call(FilePermissionTableSeeder::class);
        $this->call(CoursesPermissionSeeder::class);
        $this->call(PaymentsPermissionsSeeder::class);
        $this->call(CategoriesPermissionSeeder::class);
        $this->call(PagesPermissionTableSeeder::class);
        $this->call(ScormPermissionTableSeeder::class);
    }
}
