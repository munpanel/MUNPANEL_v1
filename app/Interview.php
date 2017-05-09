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
use Config;

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
            case 'undecided': return '已完成 (结果待定)';
            default: return '未知状态';
        }
    }

    public function scoreHTML() {
        $scoresOptions = json_decode(Reg::currentConference()->option('interview_scores'));
        $scores = json_decode($this->scores);
        $score = 0;
        $result = "";
        foreach($scoresOptions->criteria as $key => $value)
        {
            if (isset($scores->$key))
            {
                $result.= $value->name . $scores->$key . "&nbsp;";
                $score += $scores->$key * $value->weight;
            } else
                $result .= $value->name . '未评&nbsp;';
        }
        $score *= $scoresOptions->total / 5;
        $result =  "<a style='cursor: pointer;' class='details-popover' data-html='1' data-placement='right' data-trigger='click' data-original-title='详细评分 - ".number_format($score, 1, '.', '')."' data-toggle='popover' data-content='".$result."'>";
        $score = round($score * 2);
        for ($i = 0 ; $i < 5 ; $i++)
        {
            $score -= 2;
            if ($score > -1)
                $result .= "<i class='fa fa-star fa-fw'></i>";
            else if ($score == -1)
                $result .= "<i class='fa fa-star-half-o fa-fw'></i>";
            else
                $result .= "<i class='fa fa-star-o fa-fw'></i>";
        }
        $result .= "</a>";
        return $result;
    }

}
