<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * Developed by Adam Yi <xuan@yiad.am>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = ['reg_id', 'noter_id', 'content'];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function noter() {
        return $this->belongsTo('App\User', 'user_id');
    }
}
