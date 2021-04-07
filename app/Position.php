<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
        'category',
        'structure_id',
        'organization_id',
        'name',
        'p_id',
        'count',
    ];

    public function structure()
    {
        return $this->belongsTo(\App\Structure::class,'structure_id','s_id');
    }
}
