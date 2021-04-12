<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TUIRead extends Model
{
    // TaskUserItem statuses (read unread)
    protected $fillable = [
        'task_id',
        'read',
        'task_user_item_id',
        'user_id'
    ];
}
