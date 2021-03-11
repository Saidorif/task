<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = ['name','label'];
    
    public function permissions()
    {
        return $this->hasMany(\App\Permission::class, 'role_id');
    }
    
    public function permissionsWith()
    {
        return $this->hasMany(\App\Permission::class, 'role_id')->with(['controller','action']);
    }
}
