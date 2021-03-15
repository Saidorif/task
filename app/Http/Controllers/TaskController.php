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
        $result = Task::with(['user'])->orderBy('id','DESC')->paginate($limit);
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
            'users' => 'required|array',
            'users.*' => 'required|array',
            'users' => 'required|array',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $user = $request->user();
        if(empty($inputs['status'])){
            $inputs['status'] = 'draft';
        }
        $inputs['user_id'] = $user->id;
        if(!empty($inputs['users']) && count($inputs['users']) > 0){
            //some stuff
        }
        $task = Task::create($inputs);
        return response()->json(['success' => true, 'message' => 'Task created']);
    }
}
