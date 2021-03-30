<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $fillable = ['user_id','task_id','svot'];

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id')->with('position');
    }

    public function task()
    {
        return $this->belongsTo(\App\Task::class,'task_id')->with(['items','users','comments'])->where('status','!=','draft');
    }

    public function items()
    {
        return $this->hasMany(\App\TaskUserItem::class,'parent_id')->with('comments');
    }
}
