<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dailyjob extends Model
{
    protected $fillable = [
        'user_id',
        'text',
        'date',
    ];
}
