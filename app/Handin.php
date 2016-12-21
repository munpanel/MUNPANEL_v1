<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Handin extends Model
{
    protected $table='handins';
    protected $fillable = ['user_id', 'nation_id', 'assignment_id', 'content', 'handin_type', 'remark']; 

    public function committee() 
    {
        return $this->belongsTo('App\Committee');
    }
	
	public function user() 
    {
        return $this->belongsTo('App\User');
    }
	
	public function nation()
	{
		return $this->belongsTo('App\Nation');
	}

    public function assignment()
    {
		return $this->belongsTo('App\Assignment');
    }        
}
