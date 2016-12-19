<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = array('name');

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }

    public function volunteers() {
        return $this->hasMany('App\Volunteer');
    }

    public function observers() {
        return $this->hasMany('App\Observer');
    }
}
