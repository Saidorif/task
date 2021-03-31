<?php

namespace App\Http\Controllers;

use App\Structure;
use Illuminate\Http\Request;

class PositionController extends Controller
{
    public function list(Request $request)
    {
        $result = Structure::orderBy('id','DESC')->get();
        return response()->json(['success' => true,'result' => $result]);
    }
}
