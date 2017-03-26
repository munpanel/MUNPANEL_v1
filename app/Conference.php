<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conference extends Model
{
    protected $fillable = ['name', 'fullname', 'date-start', 'date-end', 'description'];

    public function committees()
    {
        return $this->hasMany('App\Committee');
    }
    
    public function delegates() 
    {
        return $this->hasMany('App\Delegate');
    }
    
    public function volunteers() 
    {
        return $this->hasMany('App\Volunteer');
    }

    public function observers() 
    {
        return $this->hasMany('App\Observer');
    }

    public function dais() 
    {
        return $this->hasMany('App\Dais');
    }

    public function forms()
    {
        return $this->hasMany('App\Form');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function option($key)
    {
        if (is_object($this->options()->where('key', $key)->first()))
            return $this->options()->where('key', $key)->first()->value;
        return null;
    }
}
