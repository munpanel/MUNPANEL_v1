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
        return $this->school->typeText().'管理' . '('. $this->school->name .')';
        return '面试官'. (empty($this->committee_id)?'':'（'.$this->committee->name.'）');
    }
}
