<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Dailyjob;

class DailyjobController extends Controller
{
    public function index(Request $request)
    {
        $result = Dailyjob::orderBy('id','DESC')->paginate(12);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function edit(Request $request, $id)
    {
        $result = Dailyjob::with(['user'])->find($id);
        return response()->json(['success' => true,'result' => $result]);
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs,[
            'text' => 'required|string',
            'date' => 'required|date',
        ]);
        $inputs['user_id'] = $request->user()->id;
        $result = Dailyjob::create($inputs);
        return response()->json(['success' => true,'message' => 'Daily job created']);
    }
}
