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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = array('name', 'payment_method');

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

    public function toPayAmount() {
        return Auth::user()->school->delegates->where('status', 'oVerified')->count() * 530 + Auth::user()->school->delegates->where('status','oVerified')->where('accomodate', 1)->count() * 510 + Auth::user()->school->volunteers->where('status','oVerified')->where('accomodate', 1)->count() * 510;

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
}
