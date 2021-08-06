<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Http\Request;
use Validator;

class StateController extends Controller
{
    public function index(Request $request)
    {
        $builder = State::query();
        $result = $builder->orderBy('id','ASC')->paginate(12);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function tree(Request $request)
    {
        $params = $request->all();
        $builder = State::query();
        $parent_id = 0;
        if(!empty($params['parent_id'])){
            $parent_id = $params['parent_id'];
        }
        $result = $builder->where(['parent_id' => $parent_id])
                        ->orderBy('id','ASC')
                        ->with(['user','children'])
                        ->paginate(12);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function edit(Request $request, $id)
    {
        $result = State::with(['user'])->find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Структура не найдена']);
        }
        return response()->json(['success' => true,'result' => $result]);
    }

    public function list(Request $request)
    {
        $result = State::with(['user'])->get();
        return response()->json(['success' => true,'result' => $result]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'name' => 'required|string',
            'parent_id' => 'nullable|integer',
            'sort' => 'nullable|integer',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $result = State::create($inputs);
        return response()->json(['success' => true,'message' => 'Структура создана']);
    }

    public function update(Request $request,$id)
    {
        $user = $request->user();
        $result = State::find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Структура не найдена']);
        }
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'name' => 'required|string',
            'parent_id' => 'nullable|integer',
            'sort' => 'nullable|integer',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $result->update($inputs);
        return response()->json(['success' => true,'message' => 'Структура обновлено']);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $result = State::find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Структура не найдена']);
        }
        $result->delete();
        return response()->json(['success' => true,'message' => 'Структура удалено']);
    }
}
