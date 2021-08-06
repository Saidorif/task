<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $fillable = ['name','parent_id','sort'];

    public function user()
    {
        return $this->hasMany(\App\User::class,'state_id');
    }

    public function children()
    {
        return $this->hasMany(\App\State::class,'parent_id')->with('children');
    }
}
