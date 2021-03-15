<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskItem extends Model
{
    protected $fillable = ['task_id','text','file'];
}
