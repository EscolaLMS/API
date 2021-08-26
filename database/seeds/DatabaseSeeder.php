<?php

namespace Database\Seeders;

use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Categories\Database\Seeders\CategoriesPermissionSeeder;

use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Payments\Database\Seeders\PaymentsSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
use EscolaLms\Pages\Database\Seeders\DatabaseSeeder as PagesDatabaseSeeder;
use EscolaLms\Cart\Database\Seeders\OrdersSeeder;
use EscolaLms\Cart\Database\Seeders\ProgressSeeder;

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
        $this->call(PermissionsSeeder::class);

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
