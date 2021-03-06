<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Interviewer extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'reg_id';

    public function reg()
    {
        return $this->belongsTo('App\reg');
    }

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function interviews() {
        return $this->hasMany('App\Interview', 'interviewer_id');
    }

    public function regText() {
        return '面试官'. (empty($this->committee_id)?'':'（'.$this->committee->name.'）');
    }

    public function nicename() {
        if (is_object($this->committee))
            return $this->reg->name().'（'.$this->committee->name.'）';
        return $this->reg->name();
    }

    static public function list() {
        $interviewers = Interviewer::whereNull('committee_id')->with('reg.user', 'committee', 'interviews')->get();
        $list = array();
        foreach ($interviewers as $interviewer)
        {
            $list['公共'][$interviewer->reg_id] = $interviewer->reg->name(). '（' . $interviewer->interviews->whereIn('status', ['assigned', 'arranged'])->count() . '）';
        }
        $interviewers = Interviewer::whereNotNull('committee_id')->with('reg.user', 'committee', 'interviews')->get();
        foreach ($interviewers as $interviewer)
        {
            $committee = $interviewer->committee->name;
            $list[$committee][$interviewer->reg_id] = $interviewer->reg->name(). '（' . $interviewer->interviews->whereIn('status', ['assigned', 'arranged'])->count() . '）';
        }
        return $list;
    }
}
