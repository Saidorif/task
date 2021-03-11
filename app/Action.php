<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    protected $fillable = ['name', 'conts_id', 'code'];

    public function controller()
    {
        return $this->belongsTo(\App\Conts::class, 'conts_id');
    }
}
