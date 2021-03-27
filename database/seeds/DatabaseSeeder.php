<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call(AdminUserSeeder::class);
         $this->call(RoleSeeder::class);
         $this->call(ControllerActionSeeder::class);
         $this->call(RegionSeeder::class);
    }
}
