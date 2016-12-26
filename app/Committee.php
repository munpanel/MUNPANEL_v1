<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = array('name');

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }
    
    public function hasDelegate($uid)
    {
        $delegates = $this->delegates;
        $user = User::find($uid);
        if (is_null($user))
            return false;
        $delegate = $user->delegate;
        if (is_null($delegate))
            return false;
        foreach ($delegates as $del)
            if ($del->user->id == $uid)
                return true;
        return false;
    }
}
