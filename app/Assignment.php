<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table='assignments';
    protected $fillable = ['nationgroup_id', 'delegategroup_id', 'committee_id', 'subject_type', 'handin_type', 'title', 'description', 'deadline'];    

    public function nationgroups()
    {
        return $this->belongsToMany('App\Nationgroup');
    }

    public function delegategroups()
    {
        return $this->belongsToMany('App\Delegategroup');
    }

    public function committees() 
    {
        return $this->belongsToMany('App\Committee');
    }

    public function handins()
    {
        return $this->hasMany('App\Handin');
    }

    public function conference()
    {
        return $this->belongsTo('App\Conference');
    }

    public function form()
    {
        return $this->belongsToMany('App\Form');
    }

    public function scope()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->nationgroups))
        {
            $nationgroups = $this->nationgroups;
            foreach ($nationgroups as $nationgroup)
            {
                $scope .= $prefix . $nationgroup->display_name;
                $prefix = ', ';
            }
        }
        if (isset($this->delegategroups))
        {
            $delegategroups = $this->delegategroups;
            foreach ($delegategroups as $delegategroup)
            {
                $scope .= $prefix . $delegategroup->display_name;
                $prefix = ', ';
            }
        }
        if (isset($this->committees))
        {
            $committees = $this->committees;
            foreach ($committees as $committee)
            {
                $scope .= $prefix . $committee->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }
    
    public function belongsToDelegate($uid) 
    {
        $delegate = Delegate::find($uid);
        if (!is_object($delegate))
            return false;
        if (isset($this->nationgroups))
        {
            $nationgroups = $this->nationgroups;
            foreach ($nationgroups as $nationgroup)
                if ($nationgroup->hasDelegate($uid))
                    return true;
        }
        if (isset($this->delegategroups))
        {
            $delegategroups = $this->delegategroups;
            foreach ($delegategroups as $delegategroup)
                if ($delegategroup->hasDelegate($uid))
                    return true;
        }
        $committees = $this->committees()->pluck('id');
        $committee = $delegate->committee;
        do {
            if ($committees->contains($committee->id))
                return true;
        } while(is_object($committee = $committee->parentCommittee));
        return false;
    }
	
}
