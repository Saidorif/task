<?php

namespace App\Http\Controllers;

use App\TaskUserItem;
use App\TaskUser;
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
}
