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
        // first populate roles & permissions
        $this->call(PermissionsSeeder::class);

        // create users
        $this->call(UserTableSeeder::class);
    }
}
