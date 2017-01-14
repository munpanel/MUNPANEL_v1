<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Good extends Model
{
    protected $table='goods';
    protected $fillable = ['name', 'enabled', 'price', 'remains'];    

    /*
    public function orders()
    {
        return $this->hasMany('App\NationDel');
    }
    */
}
