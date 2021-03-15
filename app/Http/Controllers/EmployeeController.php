<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $sql = "SELECT e.id,e.name,e.surename,e.lastname,e.birthday,e.birthplace,e.gender,e.status,e.position_id,p.id as p_id,p.name as p_name,p.subdivisions, s.name as s_name FROM `employees` as e LEFT JOIN `positions` as p ON e.position_id = p.id LEFT JOIN `structures` as s ON s.id = p.subdivisions WHERE e.deleted_at IS NULL AND e.status = 'worker' AND e.organization_id = 1";
    }
}
