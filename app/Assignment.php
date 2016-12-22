<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table='assignments';
    protected $fillable = ['nationgroup_id', 'subject_type', 'handin_type', 'title', 'description', 'deadline'];    

    public function nationgroups()
    {
        return $this->belongsToMany('App\Nationgroup');
    }

    public function committee() 
    {
        return $this->belongsTo('App\Nationgroup')->hasMany('App\Nation')->belongsTo('App\Committee');
    }

    public function handins()
    {
        return $this->hasMany('App\Handin');
    }   
    
    public function belongsToDelegate($uid) 
    {
        $nationgroups = $this->nationgroups;
        foreach ($nationgroups as $nationgroup)
            if ($nationgroup->hasDelegate($uid))
                return true;
        return false;
    }
	
}
