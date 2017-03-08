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
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = array('name', 'payment_method');

    public function user() {
        return $this->belongsTo('App\User'); //UID=1 <=> Non-Member School
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

    public function toPayAmount() {
        return Auth::user()->school->delegates->where('status', 'oVerified')->count() * 530 + Auth::user()->school->delegates->where('status','oVerified')->where('accomodate', 1)->count() * 510 + Auth::user()->school->volunteers->where('status','oVerified')->where('accomodate', 1)->count() * 510;

    }
}
