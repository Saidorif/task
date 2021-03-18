<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUserItem extends Model
{
    protected $fillable = [
        'user_id',
        'task_id',
        'task_user_id',
        'text',
        'file',
    ];
}
