<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use App\Task;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $params = $request->all();
        $user = $request->user();
        $date = date('Y-m-d');
        $category = '';
        if(!empty($params['calendar'])){
            $date = $params['calendar'];
        }
        if(!empty($params['category'])){
            $category .= ' AND category = '.$params['category'];
        }
        $start_month = Carbon::parse($date)->firstOfMonth()->format('Y-m-d');
        $end_month = Carbon::parse($date)->lastOfMonth()->format('Y-m-d');
        $result = [];
        $result['calendar'] = DB::select("SELECT COUNT(*) AS qty,exp_date FROM `tasks` WHERE exp_date BETWEEN '$start_month' AND '$end_month' AND user_id = '$user->id' GROUP BY exp_date");
        $result['kartoteka'] = Task::with(['creater','users','items','comments'])->where('exp_date','<',date('Y-m-d'))->where('status','!=','draft')->where('user_id','=',$user->id)->get();
        $result['info'] = DB::select("SELECT COUNT(id) AS total, SUM(CASE WHEN status = 'accepted' THEN 1 ELSE 0 END) AS accepted,SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) AS active,SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) AS rejected,SUM(CASE WHEN is_important = 1 THEN 1 ELSE 0 END) AS importants FROM `tasks` WHERE status != 'draft' AND user_id = '$user->id' '$category'");
        $result['chart'] = DB::select("SELECT s.name,COUNT(t.id) AS total FROM `structures` AS s LEFT JOIN `task_users` AS t ON s.id = t.structure_id GROUP BY s.id");
        $result['user'] = $user;
        $itogi = array_sum(array_column($result['chart'],'total'));
        $result['chart'][] = ['name' => 'Total','total' => $itogi];
        return response()->json(['success' => true, 'result' => $result]);
    }
}
