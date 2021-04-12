<?php

namespace App\Http\Controllers;

use App\Events\TaskCreated;
use App\Task;
use App\TaskComment;
use App\TaskItem;
use App\TaskUser;
use App\TaskUserItem;
use App\User;
use Illuminate\Support\Facades\Storage;
use Validator;
use Illuminate\Http\Request;
use DB;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $limit = !empty($params['limit']) ? (int)$params['limit'] : 12;
        $builder = Task::query();
        if(!empty($params['status'])){
            $builder->where(['status' => $params['status']]);
        }
        if(!empty($params['is_important'])){
            $builder->where(['is_important' => $params['is_important']]);
        }
        if(!empty($params['date_from']) && !empty($params['date_to'])){
            $builder->whereBetween('exp_date',[$params['date_from'],$params['date_to']]);
        }
        $downloads = [];
        if(!empty($params['download'])){
            $downloads = $builder->with(['creater','users','items','comments'])->orderBy('id','DESC')->get();
        }
        $result = $builder->with(['creater','users','items','comments'])->orderBy('id','DESC')->paginate($limit);
        return response()->json(['success' => true,'result' => $result,'downloads' => $downloads]);
    }

    public function getByDate(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'calendar' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $start_month = $inputs['calendar'];
        $result = [];
        //$result = DB::select("SELECT * FROM `tasks` WHERE exp_date BETWEEN '$start_month' AND '$start_month'");
        $result = Task::with(['users','items','creater'])->where(['exp_date' => $start_month])->get();
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function userIndex(Request $request)
    {
        $user = $request->user();
        $builder = TaskUser::query()->with(['task'])->where(['user_id' => $user->id]);
        $builder->whereHas('task', function ($query){
            $query->where('status','!=','draft');
        });
        $taskUsers = $builder->get();
        return response()->json(['success' => true,'result' => $taskUsers]);
    }

    public function edit(Request $request,$id)
    {
        $result = Task::with(['items','users','comments'])->find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Task not found']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public function userEdit(Request $request,$id)
    {
        $result = TaskUser::with(['items','user','task'])->find($id);
        if(!$result){
            return response()->json(['error' => true, 'message' => 'Task not found']);
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    public  function store(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'title' => 'required|string',
            'status' => 'nullable|string',
            'exp_date' => 'required|date',
            'users' => 'required|array',
            'users.*.user_id' => 'required|integer',
            'users.*.svot' => 'required|integer',
            'items' => 'nullable|array',
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
                $the_user = User::find($t_user['user_id']);
                if(!$the_user){
                    $task->items()->delete();
                    $task->users()->delete();
                    $task->delete();
                    return response()->json(['error' => true,'message' => 'Employee not found']);
                }
                $t_user['task_id'] = $task->id;
                $t_user['structure_id'] = $the_user->position->structure_id;
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
            'items' => 'nullable|array',
            'items.*.text' => 'nullable|string',
            'items.*.file' => 'nullable|file',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $user = $request->user();
        $users_arr = [];
        if(empty($inputs['status'])){
            $inputs['status'] = 'draft';
        }
        $inputs['user_id'] = $user->id;
        $task->update($inputs);
        if(!empty($inputs['users']) && count($inputs['users']) > 0){
            $task_users = $task->users()->delete();
            //create task users pivot
            foreach($inputs['users'] as $t_user){
                $the_user = User::find($t_user['user_id']);
                if(!$the_user){
                    return response()->json(['error' => true,'message' => 'Employee not found']);
                }
                $t_user['structure_id'] = $the_user->position->structure_id;
                $t_user['task_id'] = $task->id;
                $task_users = TaskUser::create($t_user);
                $users_arr[] = $the_user;
            }
        }
        if(!empty($inputs['items']) && count($inputs['items']) > 0){
            //delete old items
            //$task_items = $task->items()->delete();
            //create task items
            foreach($inputs['items'] as $k => $item){
                $item['task_id'] = $task->id;
                if(!empty($item['id'])){
                    $task_item = TaskItem::find($item['id']);
                }else{
                    $task_item = TaskItem::create($item);
                }
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
        if($task->status == 'active'){
            $data = [
                'createtor' => $task->creater,
                'users' => $inputs['users'],
            ];
            event(new TaskCreated($data,$task->title));
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
            $task->usersitems()->delete();
            $task->delete();
            return response()->json(['success' => true, 'message' => 'Task deleted']);
        }
        return response()->json(['success' => true, 'message' => 'Can not delete. Task is '.$task->status]);
    }

    //Send for approve to svot
    public function approve(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($task->status == 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$task->status]);
        }
        if($task->status == 'active'){
            if($task->getSvot()->user_id == $user->id){
                $task->status = 'pending';
                $task->save();
                return response()->json(['success' => true,'message' => 'Task report send for approve']);
            }
            return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }

    public function accept(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($task->status != 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$task->status]);
        }
        if($task->status == 'pending'){
            if($task->user_id == $user->id){
                $task->status = 'accepted';
                $task->save();
                return response()->json(['success' => true,'message' => 'Task report accepted']);
            }
            return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }

    public function reject(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($task->status != 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$task->status]);
        }
        $validator = Validator::make($inputs,[
            'text' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        if($task->status == 'pending'){
            if($task->user_id == $user->id){
                $task->status = 'rejected';
                $task->save();
                //create taskuseritem comment
                $task_comment = TaskComment::create([
                    'text' => $inputs['text'],
                    'user_id' => $task->user_id,
                    'task_id' => $task->id
                ]);
                return response()->json(['success' => true,'message' => 'Task report rejected']);
            }
            return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }

    public function important(Request $request,$id)
    {
        $task = Task::find($id);
        if(!$task){
            return response()->json(['error' => true,'message' => 'Task not found']);
        }
        $validator = Validator::make($request->all(),[
            'is_important' => 'required|integer',
            'comment' => 'nullable|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $task->is_important = (int)$request->input('is_important');
        if(!empty($request->input('comment'))){
            $task->comment = $request->input('comment');
        }
        $task->save();
        return response()->json(['success' => true,'message' => 'Success!!!']);
    }
}
