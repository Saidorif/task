<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'text',
    ];

    public function task()
    {
        return $this->belongsTo(\App\Task::class,'task_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }
}
