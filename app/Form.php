<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = ['conference_id', 'name', 'content'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function assignment()
    {
        return $tihs->belongsToMany('App\Assignment');
    }
}
