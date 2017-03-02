<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventtype extends Model
{
    protected $fillable = []; //Not editable. Only maunually alter database and source code

    public function events() {
        return $this->hasMany('App\Event');
    }
}
