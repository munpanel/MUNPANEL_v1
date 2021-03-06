<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Teamadmin extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'reg_id';

    public function reg()
    {
        return $this->belongsTo('App\Reg');
    }

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function regText() {
        return $this->school->typeText().'管理 ('. $this->school->name .')';
        return '面试官'. (empty($this->committee_id)?'':'（'.$this->committee->name.'）');
    }
}
