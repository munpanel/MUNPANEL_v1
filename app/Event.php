<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
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
