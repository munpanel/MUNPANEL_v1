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

    public function statusText() {
        switch($this->status)
        {
            case 'assigned': return '已分配';
            case 'arranged': return '已安排';
            case 'cancelled': return '已取消';
            case 'passed': return '已完成 (通过)';
            case 'failed': return '已完成 (未通过)';
            case 'exempted': return '已免试通过';
            case 'undecided': return '已完成 (等待评分)';
            default: return '未知状态';
        }
    }
}
