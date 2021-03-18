<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Structure extends Model
{
    protected $fillable = ['s_id','name','parent_id','organization_id'];
}
