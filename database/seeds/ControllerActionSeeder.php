<?php

use Illuminate\Database\Seeder;
use App\Conts;

class ControllerActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $controllers = [
            'DashboardController' => [
                'index',
            ],
            'UserController' => [
                'index',
                'profile',
                'list',
                'changePasword',
                'index',
            ],
            'TaskController' => [
                'index',
                'getByDate',
                'userIndex',
                'userEdit',
                'store',
                'update',
                'edit',
                'destroy',
                'accept',
                'approve',
                'reject',
            ],
            'TaskUserItemController' => [
                'store',
                'update',
                'accept',
                'approve',
                'reject',
                'edit',
                'destroy',
            ],
            'RegionController' => [
                'index',
                'list',
                'store',
                'update',
                'edit',
                'destroy',
            ],
            'AreaController' => [
                'index',
                'list',
                'store',
                'update',
                'edit',
                'destroy',
            ],
            'RoleController' => [
                'index',
                'store',
                'list',
                'edit',
                'update',
                'destroy',
            ],
            'ContsController' => [
                'index',
                'find',
                'trashed',
                'restore',
                'store',
                'update',
                'edit',
                'allContActions',
            ],
            'ActionController' => [
                'edit',
                'store',
                'trashed',
                'restore',
                'update',
                'index',
            ],
            'PermissionController' => [
                'index',
                'store',
            ]
        ];
        foreach($controllers as $key => $controller){
            $cont = Conts::create([
                'name' => $key,
                'label' => $key,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            if($cont){
                foreach($controller as $k => $item){
                    $ation = DB::table('actions')->insert([
                        'conts_id' => $cont->id,
                        'code' => $item,
                        'name' => $item,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }
}
