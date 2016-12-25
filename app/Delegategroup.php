<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Delegategroup extends Model
{
    //
    protected $table = 'delegategroups';
    protected $fillable = 'name';

    public function delegates()
    {
        return $this->belongsToMany('App\Delegate');
    }

    public function assignments()
    {
        return $this->belongsToMany('App\Assignment');
    }
}
