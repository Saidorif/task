<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $fillable = ['user_id','task_id','svot'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }
}
