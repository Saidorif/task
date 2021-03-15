<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'name',
        'surename',
        'lastname',
        'birthday',
        'birthplace',
        'gender',
        'status',
        'p_id',
        'e_id',
        'p_name',
        'status',
        'region_id',
        'area_id',
    ];

    public function region()
    {
        return $this->belongsTo(\App\Region::class,'region_id');
    }

    public function area()
    {
        return $this->belongsTo(\App\Area::class,'area_id');
    }
}
