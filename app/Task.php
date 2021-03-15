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
        return $this->hasMany(\App\Employee::class,'task_id');
    }

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }
}
