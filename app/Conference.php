<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = ['name', 'fullname', 'date-start', 'date-end', 'description'];

    public function committees()
    {
        return $this->hasMany('App\Committee');
    }

    public function forms()
    {
        return $this->hasMany('App\Form');
    }
}
