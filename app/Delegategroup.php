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

class Delegategroup extends Model
{
    //
    protected $table = 'delegategroups';
    protected $fillable = ['name', 'display_name'];

    public function delegates()
    {
        return $this->belongsToMany('App\Delegate', 'delegate_delegategroup', 'delegategroup_id', 'delegate_id');
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
        $delegates = $this->delegates;
        $user = Reg::find($uid);
        if (is_null($user))
            return false;
        $delegate = $user->delegate;
        if (is_null($delegate))
            return false;
        foreach ($delegates as $del)
            if ($del->reg->id == $uid)
                return true;
        return false;
    }
}
