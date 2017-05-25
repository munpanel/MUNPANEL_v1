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

class Volunteer extends Model
{
    protected $table='volunteer_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['user_id','conference_id','school_id','status'];

    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function regText() {
        return '志愿者';
    }

    public function nextStatus() {
        switch ($this->status) { //To-Do: configurable
            case null: return 'sVerified';
            case 'sVerified': return 'unpaid';
            case 'unpaid': return 'paid';
        }
    }

    public function statusText() {
        switch($this->status)
        {
            case 'reg': return '等待学校审核';
            case 'sVerified': return '等待组织团队审核';
            case 'oVerified':  return '组织团队审核已通过';
            case 'unpaid': return '待缴费';
            case 'paid': return '报名成功';
            case 'fail': return '审核未通过';
            default: return '未知状态';
        }
    }
}
