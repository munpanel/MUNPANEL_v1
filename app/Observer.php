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

class Observer extends Model
{
    protected $table='observer_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['user_id','conference_id','school_id','status','gender','sfz','grade','email','qq','wechat','parenttel','tel','accomodate','roommatename','roommate_user_id','notes'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }
    
    public function user() {
        return $this->belongsTo('App\User');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function regText() {
        return '观察员';
    }

    public function nextStatus() {
        switch ($this->status) { //To-Do: configurable
            case null: return 'sVerified';
            case 'sVerified': return 'unpaid';
            case 'unpaid': return 'paid';
        }
    }

}
