<?php

namespace App\Http\Controllers;

use App\Region;
use Validator;

use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function index(Request $request)
    {
        $result = Region::paginate(12);
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function list(Request $request)
    {
        $result = Region::all();
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function edit($id)
    {
        $result = Region::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Регион не найден']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->only('name');
        $result = Region::create($inputs);

        return response()->json(['success' => true, 'message' => 'Регион успешно создан']);
    }

    public function update(Request $request, $id)
    {
        $result = Region::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Регион не найден']);
        }
        $validator = Validator::make($request->all(), [
            'name'  => 'required|string',
        ]);

        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->only('name');
        $result->update($inputs);

        return response()->json(['success' => true, 'message' => 'Регион успешно обновлен']);
    }

    public function destroy(Request $request, $id)
    {
        $result = Region::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Регион не найден']);
        }
        $result->delete();

        return response()->json(['success' => true, 'message' => 'Регион удален']);
    }
}
