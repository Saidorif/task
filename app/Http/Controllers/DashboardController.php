<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $date = date('Y-m-d');
        if(!empty($params['calendar'])){
            $date = $params['calendar'];
        }
        $start_month = Carbon::parse($date)->firstOfMonth()->format('Y-m-d');
        $end_month = Carbon::parse($date)->lastOfMonth()->format('Y-m-d');
        $result = [];
        $result['calendar'] = DB::select("SELECT COUNT(*) AS qty,exp_date FROM `tasks` WHERE exp_date BETWEEN '$start_month' AND '$end_month' GROUP BY exp_date");
        return response()->json(['success' => true, 'result' => $result]);
    }
}
