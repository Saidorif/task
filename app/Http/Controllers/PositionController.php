<?php

namespace App\Http\Controllers;

use App\Position;
use App\Structure;
use Illuminate\Http\Request;
use Validator;

class PositionController extends Controller
{
    public function index(Request $request)
    {
        $limit = 20;
        if(!empty($request->input('limit'))){
            $limit = (int)$request->input('limit');
        }
        $result = Structure::orderBy('id','DESC')->paginate($limit);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function list(Request $request)
    {
        $result = Structure::orderBy('id','DESC')->get();
        return response()->json(['success' => true,'result' => $result]);
    }

    public function positionList(Request $request)
    {
        $structure = $request->input('structure_id');
        $result = Position::where(['structure_id' => $structure])->get();
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function edit(Request $request,$id)
    {
        $result = Structure::find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Structure not found']);
        }
        return response()->json(['success' => true,'result' => $result]);
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|integer'
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $result = Structure::find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Structure not found']);
        }
        $result->update($inputs);
        return response()->json(['success' => true,'message' => 'Structure updated']);
    }
}
