<?php

namespace App\Http\Controllers;

use App\Action;
use Illuminate\Http\Request;
use Validator;

class ActionController extends Controller
{

    public function index()
    {
        $result = Action::orderBy('id', 'DESC')->with(['controller'])->paginate(12);
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'conts_id' => 'required|integer',
            'code' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $controller = Action::create($inputs);
        return response()->json(['success' => true, 'message' => 'Действие создано']);
    }

    public function edit(Request $request, $id)
    {
        $action = Action::where(['id' => $id])->with('controller')->first();
        if(!$action){
            return response()->json(['error' => true, 'message' => 'Действие не найдено']);
        }
        return response()->json(['success' => true, 'result' => $action]);
    }


    public function update(Request $request, $id)
    {
        $action = Action::find($id);
        if(!$action){
            return response()->json(['error' => true, 'message' => 'Действие не найдено']);
        }
        $validator = Validator::make($request->all(),[
            'conts_id' => 'required|integer',
            'code' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $action->update($inputs);
        return response()->json(['success' => true, 'message' => 'Действие обновлено']);
    }
}