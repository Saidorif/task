<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'admin',
                'label' => 'Administrator',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'user',
                'label' => 'User',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'guest',
                'label' => 'Guest',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'minister',
                'label' => 'Minister',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];
        foreach ($roles as $key => $value) {
            DB::table('roles')->insert($value);
        }
    }
}
