<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Role;
use Validator;

class RoleController extends Controller
{
    
    public function index()
    {
        $roles = Role::paginate(12);
        return response()->json(['success' => true, 'result' => $roles]);
    }

    public function list(Request $request)
    {
        $user = $request->user();
        if($user->role->name == 'admin'){
            $roles = Role::all();
        }else{
            $roles = Role::where('name','!=','admin')->get();
        }
        return response()->json(['success' => true, 'result' => $roles]);
    }


    public function edit(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['error' => true, 'message' => 'Роль не найдена']);
        }
        return response()->json(['succcess' => true, 'result' => $role]);
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'label' => 'required|string',
            'name' => 'required|string|unique:roles,name',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }

        $inputs = $request->all();
        $role = Role::create($inputs);
        return response()->json(['success' => true, 'message' => 'Роль создана']);
    }


    public function update(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['error' => true, 'message' => 'Роль не найдена']);
        }
        $validator = Validator::make($request->all(),[
            'label' => 'required',
            'name' => 'required|string|unique:roles,name,'.$role->id
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }

        $inputs = $request->all();
        $role->update($inputs);
        return response()->json(['success' => true, 'message' => 'Роль обновлена']);
    }

    public function destroy(Request $request, $id)
    {
        $role = Role::find($id);
        if(!$role){
            return response()->json(['error' => true, 'message' => 'Роль не найдена']);
        }

        $role->delete();
        return response()->json(['success' => true, 'message' => 'Роль удалена']);
    }
}
