<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TUIComment extends Model
{
    protected $fillable = [
        'text',
        'user_id',
        'task_user_item_id',
        'type',
    ];

    public function user()
    {
        return $this->belongsTo(\App\TaskUserItem::class,'user_id');
    }
}
