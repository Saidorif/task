<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use App\Role;
use App\Action;
use Validator;

class PermissionController extends Controller
{

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'role_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $role = Role::find($request->input('role_id'));
        if(!$role){
            return response()->json(['error' => true, 'message' => 'Role not found']);
        }
        $permissions = Permission::where(['role_id' => $role->id])->with(['controller', 'action'])->get();
        $result = [
            'permissions' => $permissions,
            'role' => $role,
        ];
        return response()->json($result);
    }

    public function create()
    {
        //
    }

    public function store(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['error' => true, 'message' => 'Роль не найдена']);
        }
        $validator = Validator::make($request->all(), [
            'permissions' => 'required|array',
            'permissions.*.role_id' => 'required|integer',
            'permissions.*.conts_id' => 'required|integer',
            'permissions.*.action_id' => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $user = $request->user();
        $inputs = $request->input('permissions');
        $permits = $role->permissions;
        foreach($permits as $permit){
            $permit->delete();
        }
        foreach($inputs as $key => $input){
            $permission = new Permission();
            $permission->role_id = $input['role_id'];
            $permission->conts_id = $input['conts_id'];
            $permission->action_id = $input['action_id'];
            $permission->actions = json_encode(['create', 'index', 'store']);
            $permission->save();
        }
        return response()->json(['success' => true, 'message' => 'Разрешение создано']);
    }

    
    public function show(Permission $permission)
    {
        //
    }

    
    public function edit(Permission $permission)
    {
        //
    }

    
    public function update(Request $request, Permission $permission)
    {
        //
    }

    
    public function destroy(Permission $permission)
    {
        //
    }
}
