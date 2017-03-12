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

class Interview extends Model
{
    public $guarded = [];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function interviewer() {
        return $this->belongsTo('App\Interviewer', 'interviewer_id', 'reg_id');
    }

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

}
