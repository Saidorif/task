<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskItem;
use App\TaskUser;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $limit = !empty($params['limit']) ? (int)$params['limit'] : 12;
        $result = Task::with(['creater','users','items'])->orderBy('id','DESC')->paginate($limit);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function userIndex(Request $request)
    {
        $user = $request->user();
        $taskUsers = TaskUser::with(['task'])->where(['user_id' => $user->id])->get();
        return response()->json(['success' => true,'result' => $taskUsers]);
    }

    public function edit(Request $request,$id)
    {
        $result = Task::with(['items','users'])->find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Task not found']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public  function store(Request $request)
    {
        $inputs = $request->all();
//        $inputs['users'] = json_decode($inputs['users'],true);
//        $inputs['items'] = json_decode($inputs['items'],true);
        $validator = Validator::make($inputs,[
            'title' => 'required|string',
            'status' => 'nullable|string',
            'exp_date' => 'required|date',
            'users' => 'required|array',
            'users.*.user_id' => 'required|integer',
            'users.*.svot' => 'required|integer',
            'items' => 'required|array',
            'items.*.text' => 'nullable|string',
            'items.*.file' => 'nullable|file',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $user = $request->user();
        if(empty($inputs['status'])){
            $inputs['status'] = 'draft';
        }
        $inputs['user_id'] = $user->id;
        $task = Task::create($inputs);
        if(!empty($inputs['users']) && count($inputs['users']) > 0){
            //create task users pivot
            foreach($inputs['users'] as $t_user){
                $t_user['task_id'] = $task->id;
                $task_users = TaskUser::create($t_user);
            }
        }
        if(!empty($inputs['items']) && count($inputs['items']) > 0){
            //create task items
            foreach($inputs['items'] as $k => $item){
                $item['task_id'] = $task->id;
                $task_item = TaskItem::create($item);
                //Upload file
                if($request->hasFile('items.'.$k.'.file')){
                    $file = $request->file('items.'.$k.'.file');
                    $path = 'public/'.date('Y-m-d');
                    $file_name = time().'.'.$file->getClientOriginalExtension();
                    Storage::disk('local')->putFileAs($path, $file,$file_name);
                    $task_item->file = 'storage/'.date('Y-m-d').'/'.$file_name;
                    $task_item->save();
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Task created']);
    }

    public  function update(Request $request,$id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true,'message' => 'Task not found']);
        }
        $validator = Validator::make($request->all(),[
            'title' => 'required|string',
            'status' => 'nullable|string',
            'exp_date' => 'required|date',
            'users' => 'required|array',
            'users.*.user_id' => 'required|integer',
            'users.*.svot' => 'required|integer',
            'items' => 'required|array',
            'items.*.text' => 'nullable|string',
            'items.*.file' => 'nullable|file',
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
        $task->update($inputs);
        if(!empty($inputs['users']) && count($inputs['users']) > 0){
            $task_users = $task->users()->delete();
            //create task users pivot
            foreach($inputs['users'] as $t_user){
                $t_user['task_id'] = $task->id;
                $task_users = TaskUser::create($t_user);
            }
        }
        if(!empty($inputs['items']) && count($inputs['items']) > 0){
            //delete old items
            $task_items = $task->items()->delete();
            //create task items
            foreach($inputs['items'] as $k => $item){
                $item['task_id'] = $task->id;
                $task_item = TaskItem::create($item);
                //Upload file
                if($request->hasFile('items.'.$k.'.file')){
                    $file = $request->file('items.'.$k.'.file');
                    $path = 'public/'.date('Y-m-d');
                    $file_name = time().'.'.$file->getClientOriginalExtension();
                    Storage::disk('local')->putFileAs($path, $file,$file_name);
                    $task_item->file = 'storage/'.date('Y-m-d').'/'.$file_name;
                    $task_item->save();
                }
            }
        }
        return response()->json(['success' => true, 'message' => 'Task updated']);
    }

    public function destroy(Request $request,$id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true, 'message' => 'Task not found']);
        }
        if($task->status == 'draft'){
            //delete task items
            $task->items()->delete();
            $task->users()->delete();
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted']);
        }
        return response()->json(['success' => true, 'message' => 'Can not delete. Task is '.$task->status]);
    }
}
