<?php

namespace Database\Seeders;

use EscolaLms\Auth\Database\Seeders\UserGroupsSeeder;
use EscolaLms\Cart\Database\Seeders\OrdersSeeder;
use EscolaLms\Categories\Database\Seeders\CategoriesSeeder;
use EscolaLms\Courses\Database\Seeders\CoursesSeeder;
use EscolaLms\Courses\Database\Seeders\ProgressSeeder;
use EscolaLms\Pages\Database\Seeders\DatabaseSeeder as PagesDatabaseSeeder;
use EscolaLms\Payments\Database\Seeders\PaymentsSeeder;
use EscolaLms\Settings\Database\Seeders\DatabaseSeeder as SettingsDatabaseSeeder;
use EscolaLms\TemplatesEmail\Database\Seeders\TemplatesEmailSeeder;
use EscolaLms\Tags\Database\Seeders\TagsSeeder;
use EscolaLms\Webinar\Database\Seeders\WebinarsSeeder;
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
        $this->call(CoursesSeeder::class);
        $this->call(OrdersSeeder::class);
        $this->call(ProgressSeeder::class);
        $this->call(PaymentsSeeder::class);
        $this->call(PagesDatabaseSeeder::class);
        $this->call(SettingsDatabaseSeeder::class);
        $this->call(UserGroupsSeeder::class);
        $this->call(ConsultationsSeeder::class);
//        $this->call(TemplatesEmailSeeder::class);
        $this->call(TagsSeeder::class);
        $this->call(WebinarsSeeder::class);
    }
}
