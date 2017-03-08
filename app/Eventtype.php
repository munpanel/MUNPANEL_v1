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

class Eventtype extends Model
{
    protected $fillable = []; //Not editable. Only maunually alter database and source code

    public function events() {
        return $this->hasMany('App\Event');
    }
}
