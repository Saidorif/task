<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['title','exp_date','status'];

    protected $dates = ['exp_date'];
}
