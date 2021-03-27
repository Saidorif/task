<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUserItem extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'parent_id',
        'text',
        'file',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }

    public function task()
    {
        return $this->belongsTo(\App\Task::class,'task_id')->with(['items','users']);
    }

    public function parent()
    {
        return $this->belongsTo(\App\TaskUser::class,'parent_id');
    }

    public function getSvot()
    {
        return TaskUser::where(['task_id' => $this->task_id,'svot' => 1])->first();
    }

    public function comments()
    {
        return $this->hasMany(\App\TUIComment::class,'task_user_item_id');
    }
}
