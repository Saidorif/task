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
    ];
}
