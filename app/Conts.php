<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conts extends Model
{
    protected $fillable = ['name', 'label'];

    public function actions()
    {
        return $this->hasMany(\App\Action::class, 'conts_id');
    }
}
