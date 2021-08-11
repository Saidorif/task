<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Position;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $result['user'] = User::where(['id' => $user->id])->with(['role'])->first();
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
        $params = $request->all();
        $builder = User::query();
        if(!empty($params['position_id'])){
            $builder->where(['p_id' => $params['position_id']]);
        }
        if(!empty($params['region_id'])){
            $builder->where(['region_id' => $params['region_id']]);
        }
        if(!empty($params['area_id'])){
            $builder->where(['area_id' => $params['area_id']]);
        }
        if(!empty($params['status'])){
            $builder->where(['status' => $params['status']]);
        }
        if(!empty($params['gender'])){
            $builder->where(['gender' => $params['gender']]);
        }
        if(!empty($params['p_id'])){
            $builder->where(['p_id' => $params['p_id']]);
        }
        $result = $builder->with(['position'])->where(['role_id' => 2])->paginate(2000);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if($user->role_id == 1){
            $role_ids = [1,2,3,4];
        }else{
            $role_ids = [3,4];
        }
        $validator = Validator::make($request->all(), [
            'password'    => 'required|string|min:6',
            'confirm_password'    => 'required|string|min:6',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'surename' => 'required|string',
            'lastname' => 'nullable|string',
            'birthday' => 'nullable|string',
            'birthplace' => 'nullable|string',
            'gender' => 'required|string',
            'status' => 'required|string',
            'region_id' => 'nullable|string',
            'area_id' => 'nullable|string',
            'state_id' => 'required|integer',
            'watcher' => 'required|integer',
            'role_id' => ['required',Rule::in($role_ids)]
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        if($inputs['password'] !== $inputs['confirm_password']){
            return response()->json(['error' => true, 'message' => 'Пароли не совпадают']);
        }
        $inputs['password'] = Hash::make($inputs['password']);
        $user = User::create($inputs);
        return response()->json(['success' => true,'message' => 'User created successfully']);
    }

    public function update(Request $request,$id)
    {
        $client = User::find($id);
        if(!$client){
            return response()->json(['error' => true,'message' => 'User not found']);
        }
        $user = $request->user();
        if($user->role_id == 1){
            $role_ids = [1,2,3,4];
        }else{
            $role_ids = [3,4];
        }
        $validator = Validator::make($request->all(), [
            'password'    => 'nullable|string|min:6',
            'confirm_password'    => 'nullable|string|min:6',
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,'.$client->id,
            'surename' => 'required|string',
            'lastname' => 'nullable|string',
            'birthday' => 'nullable|string',
            'birthplace' => 'nullable|string',
            'gender' => 'required|string',
            'status' => 'required|string',
            'region_id' => 'nullable|string',
            'area_id' => 'nullable|string',
            'state_id' => 'required|integer',
            'watcher' => 'required|integer',
            'role_id' => ['required',Rule::in($role_ids)]
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        if(!empty($inputs['password']) && !empty($inputs['confirm_password'])){
            if($inputs['password'] !== $inputs['confirm_password']){
                return response()->json(['error' => true, 'message' => 'Пароли не совпадают']);
            }
            $inputs['password'] = Hash::make($inputs['password']);
        }
        $client->update($inputs);
        return response()->json(['success' => true,'message' => 'User updated successfully']);
    }

    public function edit(Request $request,$id)
    {
        $client = User::with(['role','position','area','region'])->find($id);
        if(!$client){
            return response()->json(['error' => true,'message' => 'User not found']);
        }
        return response()->json(['error' => true,'result' => $client]);
    }
}
