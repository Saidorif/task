<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Position;
use App\User;
use Illuminate\Http\Request;
use Validator;
use Hash;

class UserController extends Controller
{
    public function profile(Request $request)
    {
        $user = $request->user();
        $pers = [];
        $permissions = Permission::where(['role_id' => $user->role->id])->with(['controller', 'action'])->get();
        foreach($permissions as $k => $permission){
            $pers[$k]['action'] = $permission->action->code;
            $pers[$k]['subject'] = $permission->controller->name;
        }
        $pers = array_unique($pers, SORT_REGULAR);
        $pers = array_values($pers);
        $result['permissions'] = $pers;
        $result['user'] = User::where(['id' => $user->id])->with('role')->first();
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'structure_id'    => 'nullable|integer',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        if($request->has('structure_id')){
            $positions = Position::where('structure_id','=',$request->input('structure_id'))->pluck('id')->toArray();
            $result = User::whereIn('p_id',$positions)->with(['position'])->where(['role_id' => 2])->get();
        }else{
            $result = User::with(['position'])->where(['role_id' => 2])->get();
        }
        return response()->json(['success' => true,'result' => $result]);
    }

    public function changePasword(Request $request)
    {
        $user = $request->user();
        $validator = Validator::make($request->all(), [
            'password'    => 'required|string|min:6',
            'confirm_password'    => 'required|string|min:6',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        if($inputs['password'] !== $inputs['confirm_password']){
            return response()->json(['error' => true, 'message' => 'Пароли не совпадают']);
        }

        $user->password = Hash::make($inputs['password']);
        $user->save();
        return response()->json(['success' => true, 'message' => 'Пароль успешно изменен']);
    }

    public function index(Request $request)
    {
        $result = User::with(['position'])->where(['role_id' => 2])->paginate(20);
        return response()->json(['success' => true,'result' => $result]);
    }
}
