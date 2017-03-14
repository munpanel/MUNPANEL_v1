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
