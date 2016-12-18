<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nationgroup extends Model
{
    protected $table='nationgroups';
    protected $fillable = ['name'];    

    public function nations() 
    {
        return $this->hasMany('App\Nations');
    }      
}