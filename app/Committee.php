<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Committee extends Model
{
    protected $fillable = ['name', 'display_name', 'topic_0', 'topic_1', 'topic_sel', 'language', 'rule', 'timeframe_start', 'timeframe_end', 'session', 'description'];

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }

    public function nations() {
        return $this->hasMany('App\Nation');
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
