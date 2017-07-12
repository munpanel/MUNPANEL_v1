<?php
/**
 * Copyright (C) MUNPANEL
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
    private $_options = array();

    public function committees()
    {
        return $this->hasMany('App\Committee');
    }
    
    public function delegates() 
    {
        return $this->hasMany('App\Delegate');
    }
    
    public function delegategroups() 
    {
        return $this->hasMany('App\Delegategroup');
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

    public function regs()
    {
        return $this->hasMany('App\Reg');
    }

    public function interviewers() 
    {
        return $this->hasMany('App\Interviewer');
    }

    public function forms()
    {
        return $this->hasMany('App\Form');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function option($key, $school_id = 0)
    {
        if (isset($this->_options[$key][$school_id]))
            return $this->_options[$key][$school_id];
        if ($this->relationLoaded('options'))
            $result = $this->options->where('key', $key);
        else
            $result = $this->options()->where('key', $key);
        if ($school_id != 0)
            $result = $result->where('school_id', $school_id);
        $result = $result->first();
        if (is_object($result))
        {
            $result = $result->value;
            $this->_options[$key][$school_id] = $result;
        }
        else
            return null;
        return $result;
    }
    
    public function isPartnerAutopaired()
    {
        if ($this->option('partner_paired') > 0)
            return true;
        return false;
    }

    public function isRoommateAutopaired()
    {
        if ($this->option('roommate_paired') > 0)
            return true;
        return false;
    }
}
