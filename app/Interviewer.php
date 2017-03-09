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

class Interviewer extends Model
{
    protected $guarded = [];
    protected $primaryKey = 'reg_id';

    public function reg()
    {
        return $this->belongsTo('App\reg');
    }

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function interviews() {
        return $this->hasMany('App\Interview', 'interviewer_id');
    }

    public function regText() {
        return '面试官';
    }

    static public function list() {
        $interviewers = Interviewer::all();
        $list = array();
        foreach ($interviewers as $interviewer)
        {
            if (is_object($interviewer->committee))
                $committee = $interviewer->committee->name;
            else
                $committee = '公共';
            $list[$committee][$interviewer->reg_id] = $interviewer->reg->name(). '（' . $interviewer->interviews->whereIn('status', ['assigned', 'arranged'])->count() . '）';
        }
        return $list;
    }
}
