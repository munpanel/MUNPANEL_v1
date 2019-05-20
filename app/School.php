<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = array('name', 'payment_method');
    private $_options = array();

    /*public function user() {
        return $this->belongsTo('App\User'); //UID=1 <=> Non-Member School
    }*/

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }

    public function volunteers() {
        return $this->hasMany('App\Volunteer');
    }

    public function observers() {
        return $this->hasMany('App\Observer');
    }

    public function teamadmins() {
        return $this->hasMany('App\Teamadmin');
    }

    public function users() {
        return $this->belongsToMany('App\User');
    }

    public function options()
    {
        return $this->hasMany('App\Option');
    }

    public function typeText() {
        switch($this->type)
        {
            case 'school': return '中学';
            case 'university': return '高等学校';
            default: return '团体';
        }
    }

    public function isAdmin($conference_id = null) {
        if (is_null($conference_id)) {
            if ($this->teamadmins()->whereNull('conference_id')->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('regs')
                          ->whereRaw('regs.user_id = ' . Auth::id() . ' and regs.id=teamadmins.reg_id');
                })->count() > 0)
                return true;
            return false;
        } else {
            if ($this->teamadmins()->where('conference_id', $conference_id)->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                          ->from('regs')
                          ->whereRaw('regs.user_id = ' . Auth::id() . ' and regs.id=teamadmins.reg_id');
                })->count() > 0)
                return true;
            return false;
        }
    }

    public function option($key, $conference_id = 0)
    {
        if (isset($this->_options[$key][$conference_id]))
            return $this->_options[$key][$conference_id];
        if ($this->relationLoaded('options'))
            $result = $this->options->where('key', $key);
        else
            $result = $this->options()->where('key', $key);
        if ($conference_id != 0)
            $result = $result->where('conference_id', $conference_id);
        $result = $result->first();
        if (is_object($result))
        {
            $result = $result->value;
            $this->_options[$key][$conference_id] = $result;
        }
        else
            return null;
        return $result;
    }
}
