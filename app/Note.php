<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
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
