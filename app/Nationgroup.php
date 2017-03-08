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

class Nationgroup extends Model
{
    protected $table='nationgroups';
    protected $fillable = ['name', 'display_name'];    

    public function nations() 
    {
        return $this->belongsToMany('App\Nation', 'nationgroup_nation');
    }      

    public function assignments()
    {
        return $this->belongsToMany('App\Assignment');
    }
    
    public function documents()
    {
        return $this->belongsToMany('App\Document');
    }

    public function hasDelegate($uid)
    {
        $nations = $this->nations;
        $user = User::find($uid);
        if (is_null($user))
            return false;
        $delegate = $user->delegate;
        if (is_null($delegate))
            return false;
        $usernation = $delegate->nation;
        if (is_null($usernation))
            return false;
        $nation_id = $usernation->id;
        foreach ($nations as $nation)
            if ($nation->id == $nation_id)
                return true;
        return false;
    }
}
