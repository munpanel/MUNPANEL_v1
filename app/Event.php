<?php
/**
 * Copyright (C) Console iT
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $guarded = [];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function eventtype() {
        return $this->belongsTo('App\Eventtype');
    }

    public function text() {
        $vars = json_decode($this->content, true);
        if (isset($vars))
            return view(['template' => $this->eventtype->text], json_decode($this->content, true));
        else
            return $this->eventtype->text;
        return render(Blade::compileString($this->eventtype->text), json_decode($this->content));
    }
}
