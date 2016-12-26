<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegategroup extends Model
{
    //
    protected $table = 'delegategroups';
    protected $fillable = 'name';

    public function delegates()
    {
        return $this->belongsToMany('App\Delegate');
    }

    public function assignments()
    {
        return $this->belongsToMany('App\Assignment');
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
