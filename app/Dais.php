<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dais extends Model
{
    protected $table = 'dais_info';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id', 'school_id', 'committee_id', 'position'];

    public function committee()
    {
        return $this->belongsTo('App\Committee');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function school()
    {
        return $this->belongsTo('App\School');
    }
}
