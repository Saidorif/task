<?php

namespace App\Http\Controllers;

use App\Task;
use Validator;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $limit = !empty($params['limit']) ? (int)$params['limit'] : 12;
        $result = Task::orderBy('id','DESC')->paginate($limit);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function edit(Request $request,$id)
    {
        $result = Task::find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Task not found']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public  function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'title' => 'required|string',
            'status' => 'nullable|string',
            'exp_date' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $task = Task::create($request->all());
        return response()->json(['success' => true, 'message' => 'Task created']);
    }
}
