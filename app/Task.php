<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','exp_date','status','user_id'];

    protected $dates = ['exp_date'];

    public function files()
    {
        return $this->hasMany(\App\File::class,'task_id');
    }

    public function users()
    {
        return $this->hasMany(\App\TaskUser::class,'task_id')->with(['user','items']);
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
}
