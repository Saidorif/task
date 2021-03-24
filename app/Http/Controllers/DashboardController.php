<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $result = [];
        $result['calendar'] = DB::select("SELECT COUNT(*) AS qty,exp_date FROM `tasks` WHERE exp_date BETWEEN '2021-01-01' AND '2021-01-31' GROUP BY exp_date");
        return response()->json(['success' => true, 'result' => $result]);
    }
}
