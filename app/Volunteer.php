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
            case 'interview_assigned': return $this->interviewText() . '已分配';
            case 'interview_arranged': return $this->interviewText() . '已安排';
            case 'interview_passed': return $this->interviewText() . '通过';
            case 'interview_failed': return $this->interviewText() . '未通过';
            case 'interview_unassigned': return $this->interviewText() . '待分配';
            case 'interview_undecided': return $this->interviewText() . '结果待定';
            case 'interview_retest_assigned': return $this->interviewText() . '通过（' . $this->interviewText(true) . '已分配）';
            case 'interview_retest_arranged': return $this->interviewText() . '通过（' . $this->interviewText(true) . '已安排）';
            case 'interview_retest_passed': return $this->interviewText() . '通过（' . $this->interviewText(true) . '通过）';
            case 'interview_retest_failed': return $this->interviewText() . '通过（' . $this->interviewText(true) . '未通过）';
            case 'interview_retest_unassigned': return $this->interviewText() . '通过（' . $this->interviewText(true) . '待分配）';
            case 'interview_retest_undecided': return $this->interviewText() . '通过（' . $this->interviewText(true) . '结果待定）';
            default: return '未知状态';
        }
    }
}
