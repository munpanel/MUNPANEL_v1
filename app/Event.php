<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    private $guarded = [];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function eventtype() {
        return $this->belongsTo('App\Event');
    }

    public function text() {
        return render(Blade::compileString($this->eventtype->text), json_decode($this->content));
    }
}
