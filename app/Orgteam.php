<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orgteam extends Model
{
    protected $table = 'ot_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['reg_id', 'conference_id', 'school_id', 'position'];
    
    public function conference() {
        return $this->belongsTo('App\Conference');
    }
    
    public function reg()
    {
        return $this->belongsTo('App\reg');
    }

    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
