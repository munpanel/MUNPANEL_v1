<?php

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
