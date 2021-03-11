<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Area;
use Validator;

class AreaController extends Controller
{
    public function index(Request $request)
    {
        $result = Area::with('region')->paginate(12);
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function list(Request $request)
    {
        $result = Area::with('region')->get();
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function regionby(Request $request)
    {
        $user = $request->user();

        if($user){
            if($user->role_id == 1){
                $validator = Validator::make($request->all(), [
                    'region_id'  => 'required|integer'
                ]);

                if($validator->fails()){
                    return response()->json(['error' => true, 'message' => $validator->messages()]);
                }
                $result = Area::where(['region_id' => $request->input('region_id')])->get();
            }else{
                $result = Area::where(['region_id' => $user->region_id])->get();
            }
        }else{
            $result = Area::where(['region_id' => $request->region_id])->get();
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function edit($id)
    {
        $result = Area::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Район не найден']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'region_id'  => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->only('name','region_id');
        $result = Area::create($inputs);

        return response()->json(['success' => true, 'message' => 'Район успешно создан']);
    }

    public function update(Request $request, $id)
    {
        $result = Area::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Район не найден']);
        }
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
            'region_id'  => 'required|integer',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->only('name','region_id');
        $result->update($inputs);

        return response()->json(['success' => true, 'message' => 'Район успешно обновлен']);
    }

    public function destroy(Request $request, $id)
    {
        $result = Area::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Район не найден']);
        }
        $result->delete();

        return response()->json(['success' => true, 'message' => 'Район удален']);
    }
}
