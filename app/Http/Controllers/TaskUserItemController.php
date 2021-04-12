<?php

namespace App\Http\Controllers;

use App\Task;
use App\TaskUserItem;
use App\TaskUser;
use App\TUIComment;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;

class TaskUserItemController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'text' => 'nullable|string',
            'task_id' => 'required|integer',
            'parent_id' => 'required|integer',
            'file' => 'nullable|file',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $user = $request->user();
        $inputs['user_id'] = $user->id;
        $task = Task::find((int)$inputs['task_id']);
        if(!$task){
            return response()->json(['error' => true,'message' => 'Task not found']);
        }
        if($task->status != 'active'){
            return response()->json(['error' => true,'message' => 'Task is not active']);
        }
        $tuitem = TaskUserItem::create($inputs);
        //Upload file
        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = 'public/'.date('Y-m-d');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            Storage::disk('local')->putFileAs($path, $file,$file_name);
            $tuitem->file = 'storage/'.date('Y-m-d').'/'.$file_name;
            $tuitem->save();
        }
        return response()->json(['success' => true,'message' => 'Task item created successfully','result' => $tuitem]);
    }

    public function update(Request $request,$id)
    {
        $tuitem = TaskUserItem::find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        $validator = Validator::make($request->all(),[
            'text' => 'nullable|string',
            'task_id' => 'required|integer',
            'parent_id' => 'required|integer',
            'file' => 'nullable|file',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs = $request->all();
        $user = $request->user();
        $inputs['user_id'] = $user->id;
        $tuitem->update($inputs);
        //Upload file
        if($request->hasFile('file')){
            $file = $request->file('file');
            $path = 'public/'.date('Y-m-d');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            Storage::disk('local')->putFileAs($path, $file,$file_name);
            $tuitem->file = 'storage/'.date('Y-m-d').'/'.$file_name;
            $tuitem->save();
        }
        return response()->json(['success' => true,'message' => 'Task item updated successfully','result' => $tuitem]);
    }

    public function edit(Request $request,$id)
    {
        $tuitem = TaskUser::with(['task'])->find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($tuitem->read == 0){
            $tuitem->read = 1;
            $tuitem->save();
        }
        return response()->json(['success' => true,'result' => $tuitem]);
    }

    public function destroy(Request $request,$id)
    {
        $tuitem = TaskUserItem::find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        $tuitem->delete();
        return response()->json(['success' => true,'message' => 'Deleted successfully']);
    }

    //Send for approve to svot
    public function approve(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $tuitem = TaskUserItem::find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($tuitem->status == 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$tuitem->status]);
        }
        if($tuitem->user_id == $user->id){
            $tuitem->status = 'pending';
            $tuitem->save();
            return response()->json(['success' => true,'message' => 'Task report send for approve']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }

    public function accept(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $tuitem = TaskUserItem::find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($tuitem->status != 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$tuitem->status]);
        }
        $svot = $tuitem->getSvot();
        if($svot->user_id == $user->id){
            $tuitem->status = 'accepted';
            $tuitem->save();
            return response()->json(['success' => true,'message' => 'Task report accepted']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }

    public function reject(Request $request,$id)
    {
        $inputs = $request->all();
        $user = $request->user();
        $tuitem = TaskUserItem::find($id);
        if(!$tuitem){
            return response()->json(['error' => true, 'message' => 'Not found']);
        }
        if($tuitem->status != 'pending'){
            return response()->json(['error' => true, 'message' => 'Report already '.$tuitem->status]);
        }
        $validator = Validator::make($inputs,[
            'text' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $svot = $tuitem->getSvot();
        if($svot->user_id == $user->id){
            $tuitem->status = 'rejected';
            $tuitem->save();
            //create taskuseritem comment
            $tui_comment = TUIComment::create([
                'text' => $inputs['text'],
                'user_id' => $tuitem->user_id,
                'task_user_item_id' => $tuitem->id
            ]);
            return response()->json(['success' => true,'message' => 'Task report rejected']);
        }
        return response()->json(['error' => true, 'message' => 'Forbidden!!!']);
    }
}
