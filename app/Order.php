<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    public $incrementing = false;
    public $guarded = [];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function items() {
        return json_decode($this->content, true);
    }

}
