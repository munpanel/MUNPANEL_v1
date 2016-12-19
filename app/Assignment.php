<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $table='assignments';
    protected $fillable = ['committee_id', 'type', 'title', 'description', 'deadline'];    

    public function committee() 
    {
        return $this->belongsTo('App\Committee');
    }

    public function handins()
    {
		return $this->hasMany('App\Handin');
    }        
	
	// TODO: 添加其他的关联关系（如果有或需要）
}