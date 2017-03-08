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
use Illuminate\Support\Collection;

class Committee extends Model
{
    protected $fillable = ['conference_id', 'name', 'display_name', 'topic_0', 'topic_1', 'topic_sel', 'language', 'rule', 'capacity', 'father_committee_id', 'timeframe_start', 'timeframe_end', 'session', 'description', 'is_allocated'];

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }
    
    public function allDelegates()
    {
        $result = new Collection;
        $result->push($this->delegates);
        foreach ($this->childCommittees as $cc)
        {
            $result->push($this->allDelegates())
        }
        return $result;
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

    public function conference()
    {
        return $this->belongsTo('App\Conference');
    }

    public function childCommittees()
    {
        return $this->hasMany('App\Committee', 'father_committee_id');
    }

    public function parentCommittee()
    {
        return $this->belongsTo('App\Committee', 'father_committee_id');
    }

    public function emptyNations() {
        $nations = $this->nations;
        return $nations->reject(function ($nation) {
            return !$nation->delegates->isEmpty();
        })
        ->map(function ($nation) {
                return $nation;
        });
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
