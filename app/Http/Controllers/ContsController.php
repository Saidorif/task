<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Conts;
use Validator;

class ContsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $result = Conts::orderBy('id', 'DESC')->paginate(12);
        return response()->json(['success' => true, 'result' => $result]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function find(Request $request)
    {
        $params = $request->all();
        $builder = Conts::query();
        if(count($params) > 0){
            if(!empty($params['name'])){
                $builder->where('name','LIKE','%'.$params['name'].'%');
            }
            $result = $builder->orderBy('id','DESC')->get();
        }else{
            $result = [];
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:10|unique:conts,name',
            'label' => 'required|string',
        ]);
        if($validator->fails()){
            return response()->json(['error' => true, 'message' => $validator->messages()]);
        }

        $inputs = $request->all();
        $inputs['created_by'] = $request->user()->id;
        $controller = Conts::create($inputs);
        return response()->json(['success' => true, 'message' => 'Контроллер создан']);
    }

    
    public function allContActions(Request $request)
    {
        $controllers = Conts::all();
        $result = [];
        foreach($controllers as $key => $controller){
            $result[$key][$controller->name] = $controller->actions;
            $result[$key]['cont_id'] = $controller->id;
        }
        return response()->json(['success' => true, 'result' => $result]);
    }

    
    public function edit(Request $request, $id)
    {
        $controller = Conts::find($id);
        if(!$controller){
            return response()->json(['error' => true, 'message' => 'Контроллер не найден']);
        }
        return response()->json(['success' => true, 'result' => $controller]);
    }

    
    public function update(Request $request, $id)
    {
        $controller = Conts::find($id);
        if(!$controller){
            return response()->json(['error' => true, 'message' => 'Контроллер не найден']);
        }
            
        $inputs = $request->all();
        $inputs['updated_by'] = $request->user()->id;
        $controller->update($inputs);
        return response()->json(['success' => true, 'message' => 'Контроллер обновлен']);
    }

    
    public function destroy(Conts $conts)
    {
        //
    }
}
