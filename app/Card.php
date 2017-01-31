<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $incrementing = false;
    public $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

}
