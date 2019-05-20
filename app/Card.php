<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    public $incrementing = false;
    public $guarded = [];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

}
