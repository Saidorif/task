<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Dailyjob;
use Illuminate\Support\Facades\DB;
class DailyjobController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $params = $request->all();
        $builder = Dailyjob::query();
        if(!empty($params['date'])){
            $in_date = date("Y-m-d", strtotime($params['date']));
            $builder->where(['date' => $in_date]);
        }
        $result = $builder->where(['user_id' => $user->id])->orderBy('date','DESC')->paginate(12);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function edit(Request $request, $id)
    {
        $result = Dailyjob::with(['user'])->find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Not found']);
        }
        return response()->json(['success' => true,'result' => $result]);
    }

    public function list(Request $request)
    {
        $result = Dailyjob::with(['user'])->get();
        return response()->json(['success' => true,'result' => $result]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'text' => 'required|string',
            'date' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs['date'] = date("Y-m-d", strtotime($inputs['date']));
        $dailyjob = Dailyjob::where('date','=',$inputs['date'])->where(['user_id' => $user->id])->first();
        if($dailyjob){
            return response()->json(['error' => true,'message' => 'Сиз ушбу сана учун кунлик хисоботни  ёзгансиз']);
        }
        $inputs['user_id'] = $request->user()->id;
        $result = Dailyjob::create($inputs);
        return response()->json(['success' => true,'message' => 'Создано ежедневное задание']);
    }

    public function update(Request $request,$id)
    {
        $user = $request->user();
        $result = Dailyjob::where(['user_id' => $user->id])->find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Not found']);
        }
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'text' => 'required|string',
            'date' => 'required|date',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true,'message' => $validator->messages()]);
        }
        $inputs['date'] = date("Y-m-d", strtotime($inputs['date']));
        $dailyjob = Dailyjob::where('date','=',$inputs['date'])->where(['user_id' => $user->id])->where('id','!=',$id)->first();
        if($dailyjob){
            return response()->json(['error' => true,'message' => 'Сиз ушбу сана учун кунлик хисоботни  ёзгансиз']);
        }
        $inputs['user_id'] = $user->id;
        $result->update($inputs);
        return response()->json(['success' => true,'message' => 'Ежедневное задание обновлено']);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $result = Dailyjob::where(['user_id' => $user->id])->find($id);
        if(!$result){
            return response()->json(['error' => true,'message' => 'Not found']);
        }
        $result->delete();
        return response()->json(['success' => true,'message' => 'Ежедневное задание удалено']);
    }
}
