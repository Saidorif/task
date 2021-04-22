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

    public function user()
    {
        return $this->belongsTo(\App\User::class,'user_id');
    }
}
