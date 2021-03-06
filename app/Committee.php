<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class Committee extends Model
{
    protected $fillable = ['conference_id', 'name', 'display_name', 'topic_0', 'topic_1', 'topic_sel', 'language', 'rule', 'capacity', 'father_committee_id', 'delegategroup_id', 'timeframe_start', 'timeframe_end', 'session', 'description', 'is_allocated'];

    public function delegates() {
        return $this->hasMany('App\Delegate');
    }
    
    public function allDelegatesQuery()
    {

        $coms = $this->allCommittees(['id']);
        return Delegate::whereIn('committee_id', $coms->pluck('id')); 
    }

    public function allCommittees($columns = ['*'])
    {
        return Cache::remember('allCommitees_'.$this->id.'_'.implode($columns, '_'), 10, function () use ($columns)  {
            if (!in_array('id', $columns) || in_array('*', $columns)) {
                array_push($columns, 'id');
            }
            $coms = eloquent_collect([$this]);
            $sub_coms = Committee::where('father_committee_id', $this->id)->get($columns);
            while ($sub_coms->count() > 0) {
                $coms = $coms->merge($sub_coms);
                $sub_coms = Committee::whereIn('father_committee_id', $sub_coms->pluck('id'))->get($columns);
            }
            return $coms;
        });
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

    public function bindDelegategroup()
    {
        return $this->belongsTo('App\Delegategroup');
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

    public function createDelGroup()
    {
        $delgroup = Delegategroup::find($this->id);
        if (is_null($delgroup))
            $delgroup = new Delegategroup;
        $delgroup->id = $this->id;
        $delgroup->name = $this->name . '代表';
        $delgroup->display_name = $this->display_name . '报名代表';
        $delgroup->save();
    }

    public function belongs($fatherID)
    {
        if ($this->id == $fatherID)
            return true;
        if (isset($this->father_committee_id))
            return $this->parentCommittee->isChild($fatherID);
        return false;
    }
}
