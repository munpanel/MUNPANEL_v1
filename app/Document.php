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

class Document extends Model
{
    protected $table='documents';
    protected $fillable = ['nationgroup_id', 'delegategroup_id', 'committee_id', 'title', 'description', 'path', 'views', 'downloads'];    

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
        if (isset($this->committees))
        {
            $committees = $this->committees;
            foreach ($committees as $committee)
                if ($committee->hasDelegate($uid))
                    return true;
        }
        return false;
    }
}
