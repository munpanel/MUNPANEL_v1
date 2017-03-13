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

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $table='delegate_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['reg_id','conference_id','school_id','status','committee_id','partner_user_id'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function nation() {
        return $this->belongsTo('App\Nation');
    }

    public function user() {
        return $this->reg->user;
    }
    
    public function reg() {
        return $this->belongsTo('App\Reg');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function individual_assignments() {
        return $this->hasMany('App\Assignment');
    }

    public function nationgroups() {
        return $this->nation->nationgroups();
    }

    public function delegategroups() {
        return $this->belongstoMany('App\Delegategroup', 'delegate_delegategroup', 'delegate_id', 'delegategroup_id');
    }

    public function interviews() {
        return $this->hasMany('App\Interview', 'reg_id', 'reg_id');
    }

    public function cards() {
        return $this->hasMany('App\Card', 'user_id');
    }

    public function partner() {
        return $this->belongsTo('App\Reg', 'partner_reg_id');
    }

    public function regText() {
        return '代表（'.$this->committee->name.'）';
    }

    public function nextStatus() {
        switch ($this->status) { //To-Do: configurable
            case null: return 'sVerified';
            case 'sVerified': return 'oVerified';
            case 'oVerified': return 'unpaid';
            case 'unpaid': return 'paid';
        }
    }

    public function interviewStatus($retest = false) {
        $interview = $this->interviews()->where('retest', $retest)->orderBy('created_at', 'dsc')->first();
        if (is_object($interview))
        {
            switch($interview->status)
            {
                case 'assigned': $status = 'assigned'; break;
                case 'arranged': $status = 'arranged'; break;
                case 'passed': $status = 'passed'; break;
                case 'cancelled': $status = 'unassigned'; break;
                case 'exempted': $status = 'passed'; break;
                case 'failed': $status = 'failed'; break;
                case 'undecided': $status = 'undecided'; break;
                case 'retest': $status = $this->interviewStatus(true);
            }
        } else {
            $status = 'unassigned';
        }
        if($retest)
            return 'retest_' . $status;
        return 'interview_' . $status;
    }

    public function interviewText($retest = false) {
        $count = $this->interviews()->where('retest', $retest)->where('status', 'failed')->count();
        switch($count)
        {
            case 0: $time = ''; break;
            case 1: $time = '二次'; break;
            case 2: $time = '三次'; break;
            case 3: $time = '四次'; break;
            case 4: $time = '五次'; break;
            case 5: $time = '六次'; break;
            case 6: $time = '七次'; break;
            case 7: $time = '八次'; break;
            case 8: $time = '九次'; break;
            case 9: $time = '十次'; break;
            default: $time = $count;
        }
        if ($retest)
            return $time . '高阶面试';
        return $time . '面试';
    }

    public function realStatus() {
        switch($this->status) { //To-Do: configurable
            case 'oVerified': return $this->interviewStatus();
            default: return $this->status;
        }
    }

    public function statusText() {
        switch($this->realStatus())
        {
            case 'reg': return '等待学校审核';
            case 'sVerified': return '等待组织团队审核';
            case 'oVerified':  return '组织团队审核已通过';
            case 'unpaid': return '待缴费';
            case 'paid': return '报名成功';
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

    public function assignments() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $assignments = $nationgroup->assignments;
                    if (isset($assignments))
                        foreach ($assignments as $assignment)
                            $result->push($assignment);
                }
            }
        }
        $assignments = $this->committee->assignments;
        if (isset($assignments))
        {
            foreach ($assignments as $assignment)
                $result->push($assignment);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $assignments = $delegategroup->assignments;
                if (isset($assignments))
                    foreach ($assignments as $assignment)
                        $result->push($assignment);
            }
        }
        return $result->unique()->sortBy('id');
    }
    
    public function assignPartnerByName() 
    {
        // TODO: 如果委员会为单带，return
        // TODO: 重写所有的 partnername
        $this->partner_user_id = null;
        if (isset($this->partnername))
        {
            $partner_name = $this->partnername;
            $myname = $this->user->name;
            // TODO: 对于带空格的partnername值，在此if表达式外增加foreach表达式以逐一处理
            // TODO: 重写以下 1 行
            $partners = User::where('name', $partner_name);
            $count = $partners->count();
            if ($count == 0) 
            {
                $notes = "{'reason':'未找到搭档$partner_name" . "的报名记录'}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname . "&#09;0&#09;搭档姓名$partner_name&#09;未找到搭档的报名记录";
            }
            $partner = $partners->first();
            if ($count > 1)
            {
                foreach ($partners as $partner1)
                {
                    if ($partner1->type != 'delegate') continue;                        // 排除非代表搭档
                    if ($partner1->delegate->committee != $this->committee) continue;   // 排除非本委员会搭档
                    if ($delpartner->status != 'paid' && $delpartner->status != 'oVerified') continue;
                    $partner = $partner1;
                    break;
                }
            }
            if ($partner->id == $this->user->id)                                 // 排除自我配对
            {
                $notes = "{'reason':'$myname" . "申报的搭档与报名者本人重合'}";
                $this->reg->addEvent('partner_auto_fail', $notes); 
                return $myname  ."&#09;".$partner->id . "&#09;自我配对";
            }
            if ($partner->type != 'delegate') //continue;                        // 排除非代表搭档
            {
                $notes = "{'reason':'$partner_name" . "并未以代表身份报名'}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;不是代表";                
            }
            $delpartner = $partner->delegate;
            if ($delpartner->status != 'paid' && $delpartner->status != 'oVerified')   // 排除未通过审核搭档
            {
                $notes = "{'reason':'搭档$partner_name" . "的报名未通过审核'}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;未通过审核";
            }
            if ($delpartner->committee != $this->committee) //continue;          // 排除非本委员会搭档
            {
                $notes = "{'reason':'$partner_name" . "与$myname" . "并非同一委员会'}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname  ."&#09;".$partner->id ."&#09;搭档姓名$partner_name&#09;不同委员会";
            }
            if (is_null($delpartner->partnername))                               // 如果对方未填搭档，自动补全
                $delpartner->partnername = $myname;
            if ($delpartner->partnername != $myname) //continue;                 // 排除多角搭档
            {
                $notes = "{'reason':'$partner_name" . "申报的搭档并非$myname" . "本人'}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;多角搭档";                
            }
            $this->partner_user_id = $partner->id;
            $this->save();
            $this->reg->addEvent('partner_auto_success', '');
            $delpartner->partner_user_id = $this->user->id;
            $delpartner->save();
//            return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;成功";
        }
//        return $this->user->name . "&#09;未填写搭档姓名";
    }
    
    public function documents() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $documents = $nationgroup->documents;
                    if (isset($documents))
                        foreach ($documents as $document)
                            $result->push($document);
                }
            }
        }
        $documents = $this->committee->documents;
        if (isset($documents))
        {
            foreach ($documents as $document)
                $result->push($document);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $documents = $delegategroup->documents;
                if (isset($documents))
                    foreach ($documents as $document)
                        $result->push($document);
            }
        }
        return $result->unique()->sortBy('id');
    }

    public function scopeDelegateGroup()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->delegategroups))
        {
            $delegategroups = $this->delegategroups;
            foreach($delegategroups as $delegategroup)
            {
                $scope .= $prefix . $delegategroup->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }

}
