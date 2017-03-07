<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $guarded = [];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }
}
