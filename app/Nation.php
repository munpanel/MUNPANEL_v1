<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table='nations';
    protected $fillable = ['committee_id', 'name', 'nationgroup_id','conpetence', 'veto_power', 'attendance'];    

    public function committee() 
    {
        return $this->belongsTo('App\Committee');
    }

    public function delegates()
    {
        return $this->hasMany('App\NationDel');
    }
    
    public function nationgroups()
    {
        return $this->belongstoMany('App\Nationgroup', 'nationgroup_nation');
    }
}
