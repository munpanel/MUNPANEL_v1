<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Eventtype extends Model
{
    public $incrementing = false;
    protected $fillable = []; //Not editable. Only maunually alter database and source code

    public function events() {
        return $this->hasMany('App\Event');
    }
}
