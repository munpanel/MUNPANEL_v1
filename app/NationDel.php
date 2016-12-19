<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NationDel extends Model
{
    protected $table='nation_delegate';
    protected $primaryKey = 'user_id';
    //protected $incrementing = false;
    protected $fillable = ['user_id', 'school_id', 'committee_id', 'nation_id'];    
    
    public function user() 
    {
        return $this->belongsTo('App\User');
    }

    public function school() 
    {
        return $this->belongsTo('App\School');
    }
	
	public function nation()
	{
		return $this->belongsTo('App\Nation');
	}
}
