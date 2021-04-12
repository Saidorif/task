<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TaskUser extends Model
{
    protected $fillable = ['user_id','task_id','svot','structure_id','read'];

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

    public function tuiread()
    {
        return $this->hasMany(TUIRead::class,'task_user_id');
    }

    public function reads()
    {
        $user_id = request()->user()->id;
        return $this->tuiread()->where('read','=',1)->where('user_id','=',$user_id);
    }

    public function unreads()
    {
        $user_id = request()->user()->id;
        return $this->tuiread()->where('read','=',0)->where('user_id','=',$user_id);
    }
}
