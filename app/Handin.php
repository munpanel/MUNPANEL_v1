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
