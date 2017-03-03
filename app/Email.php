<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    private $guarded = [];

    public function send()
    {
        Mail::to($this->receiver)->queue($this);
    }
}
