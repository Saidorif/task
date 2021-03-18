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
    ];

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }

    public function task()
    {
        return $this->belongsTo(\App\Task::class,'task_id');
    }

    public function parent()
    {
        return $this->belongsTo(\App\TaskUserItem::class,'parent_id');
    }
}
