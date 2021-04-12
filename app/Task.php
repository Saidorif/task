<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','exp_date','status','user_id','is_important','comment','read'];

    protected $dates = ['exp_date'];

    public function files()
    {
        return $this->hasMany(\App\File::class,'task_id');
    }

    public function users()
    {
        return $this->hasMany(\App\TaskUser::class,'task_id')->with(['user','items'])->withCount(['unreads']);
    }

    public function usersitems()
    {
        return $this->hasMany(\App\TaskUserItem::class,'task_id');
    }

    public function creater()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }

    public function items()
    {
        return $this->hasMany(\App\TaskItem::class,'task_id');
    }

    public function comments()
    {
        return $this->hasMany(\App\TaskComment::class,'task_id');
    }

    public function getSvot()
    {
        return TaskUser::where(['task_id' => $this->id,'svot' => 1])->first();
    }

    public function tuiread()
    {
        return $this->hasMany(\App\TUIRead::class,'task_id');
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
