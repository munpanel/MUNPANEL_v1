<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table='nations';
    protected $fillable = ['committee_id', 'name', 'conpetence', 'veto_power', 'attendance'];    

    public function committee() 
    {
        return $this->belongsTo('App\Committee');
    }

    public function delegates()
    {
        return $this->hasMany('App\Delegate');
    }
    
    public function nationgroups()
    {
        return $this->belongstoMany('App\Nationgroup', 'nationgroup_nation');
    }
    
    public function scopeDelegate()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->delegates))
        {
            $delegates = $this->delegates;
            foreach($delegates as $delegate)
            {
                $scope .= $prefix . $delegate->user->name;
                $prefix = ', ';
            }
        }
        return $scope;
    }

    public function scopeNationGroup()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->nationgroups))
        {
            $nationgroups = $this->nationgroups;
            foreach($nationgroups as $nationgroup)
            {
                $scope .= $prefix . $nationgroup->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }
}
