<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    public $guarded = [];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function interviewer() {
        return $this->belongsTo('App\Reg', 'interviewer_id');
    }

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

}
