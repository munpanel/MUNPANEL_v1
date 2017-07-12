<?php
/**
 * Copyright (C) MUNPANEL
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
use Illuminate\Support\Facades\DB;
use App\Document;

class Delegate extends Model
{
    protected $table='delegate_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['reg_id','conference_id','school_id','status','committee_id','partner_reg_id', 'seat_locked'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function nation() {
        return $this->belongsTo('App\Nation');
    }

    public function assignedNations() {
        return $this->belongstoMany('App\Nation', 'delegate_nation', 'delegate_id', 'nation_id');
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
        return $this->belongsTo('App\Delegate', 'partner_reg_id');
    }

    public function regText() {
        return '代表（'.$this->committee->display_name.'）';
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
        if ($this->relationLoaded('interviews'))
            $interview = $this->interviews->where('retest', $retest)->sortByDesc('created_at')->first();
        else
            $interview = $this->interviews()->where('retest', $retest)->orderBy('created_at', 'desc')->first();
        $status = 'unassigned';
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
        }
        if($retest)
            return 'interview_retest_' . $status;
        return 'interview_' . $status;
    }

    public function seatStatus() {
        if (is_object($this->nation))
            return $this->seat_locked?'seat_locked':'seat_selected';
        else
        {
            if ($this->relationLoaded('assignedNations'))
                $nations = $this->assignedNations;
            else
                $nations = $this->assignedNations();
            if ( $nations->count() > 0 )
            {
                if ( $nations->where('status', '=', 'open')->count() > 0 )
                    return 'seat_assigned';
                return 'seat_unavailable';
            }
            if ($this->relationLoaded('interviews'))
                $interviews = $this->interviews;
            else
                $interviews = $this->interviews();
            return $this->interviewStatus($interviews->where('retest', true)->count() > 0);
        }
    }

    public function interviewText($retest = false) {
        if ($this->relationLoaded('interviews'))
            $count = $this->interviews;
        else
            $count = $this->interviews();
        $count = $count->where('retest', $retest)->whereIn('status', ['passed', 'failed'])->count();
        switch($count)
        {
            case 0:
            case 1: $time = ''; break;
            case 2: $time = '二次'; break;
            case 3: $time = '三次'; break;
            case 4: $time = '四次'; break;
            case 5: $time = '五次'; break;
            case 6: $time = '六次'; break;
            case 7: $time = '七次'; break;
            case 8: $time = '八次'; break;
            case 9: $time = '九次'; break;
            case 10: $time = '十次'; break;
            default: $time = $count . ' 次';
        }
        if ($retest)
            return $time . '高阶面试';
        return $time . '面试';
    }

    public function realStatus() {
        switch($this->status) { //To-Do: configurable
            case 'oVerified': $result = $this->seatStatus();break; //return $this->interviewStatus($this->interviews()->where('retest', true)->count() > 0);
            default: $result = $this->status;
        }
        return $result;
    }

    public function statusText() {
        switch($this->realStatus())
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
            case 'seat_assigned': return '席位已分配';
            case 'seat_selected': return '席位已选择';
            case 'seat_locked': return '席位已锁定';
            case 'seat_unavailable': return '席位无可选';
            default: return '未知状态';
        }
        //Cache::put('realStatusforReg'.$this->reg_id, $result, 1440);
    }

    public function canAssignSeats($nation = null) {
        if ($this->relationLoaded('interviews'))
            $interviews = $this->interviews;
        else
            $interviews = $this->interviews();
        switch($this->interviewStatus($interviews->where('retest', true)->count() > 0))
        {
            case 'oVerified':
            case 'interview_passed':
            case 'interview_exempted':
            case 'interview_retest_assigned':
            case 'interview_retest_arranged':
            case 'interview_retest_passed':
            case 'interview_retest_failed':
            case 'interview_retest_unassigned':
            case 'interview_retest_undecided':
            case 'interview_retest_exempted':
                if (Http\Controllers\RoleAllocController::delegates()->contains('reg_id', $this->reg_id))
                {
                    if (is_object($nation))
                    {
                        if ($nation->conference_id != $this->conference_id)
                            return false;
                        //if ($nation->locked)
                        if ($nation->status == 'locked')
                            return false;
                        $max = $nation->committee->maxAssignList;
                        if ($this->assignedNations->count() >= $max && $max != -1)
                            return false;
                        if (Reg::current()->type == 'dais' && $nation->committee_id != $this->committee_id)
                            return false;
                    }
                    return true;
                }
            default:
                return false;
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
        $committee = $this->committee;
        do {
            $assignments = $committee->assignments;
            if (isset($assignments))
            {
                foreach ($assignments as $assignment)
                    $result->push($assignment);
            }
        } while(is_object($committee = $committee->parentCommittee));
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

    public function assignPartnerByName($option = null)
    {
        if ($option == null)
            $option = json_decode('{"one_empty":"autofill","mf_roommate":"false","status_required":"oVerified"}');
        // 如果委员会为单带，return
        $myname = $this->user()->name;
        if (!$this->committee->is_dual)
            return "$myname &#09;0000&#09;所属会场为单代表制";
        $this->partner_reg_id = null;
        $partner_name = $this->reg->getInfo('conference.partnername');
        if (!empty($partner_name))
        {
            // TODO: 对于带空格的partnername值，在此if表达式外增加foreach表达式以逐一处理
            $partners = User::where('name', $partner_name)->get()->pluck(['id']);
            $partners_reg = Reg::whereIn('user_id', $partners)->where('conference_id', Reg::currentConferenceID())->whereIn('type', ['delegate', 'observer', 'volunteer'])->get();
            $count = $partners_reg->count();
            if ($count == 0)
            {
                $notes = "{\"reason\":\"未找到搭档$partner_name" . "的报名记录\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;0000&#09;搭档姓名$partner_name&#09;未找到搭档的报名记录";
            }
            foreach ($partners_reg as $partner1)
            {
                if ($partner1->type != 'delegate') continue;                        // 排除非代表搭档
                if ($partner1->delegate->committee != $this->committee) continue;   // 排除非本委员会搭档
                if (!in_array($partner1->delegate->status, ['paid', 'oVerified'])) continue;
                if (is_object($partner))
                {
                    $notes = "{\"reason\":\"存在多个符合条件的$partner_name" . "以代表身份报名\"}";
                    $this->reg->addEvent('partner_auto_fail', $notes);
                    return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;同名同姓无法配对";
                }
                $partner = $partner1;
            }
            if (!is_object($partner))
                $partner = $partners_reg->first();
            if ($partner->id == $this->reg_id)                                 // 排除自我配对
            {
                $notes = "{\"reason\":\"$myname" . "申报的搭档与报名者本人重合\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;$partner->id &#09;自我配对";
            }
            if ($partner->type != 'delegate') //continue;                        // 排除非代表搭档
            {
                $notes = "{\"reason\":\"$partner_name" . "并未以代表身份报名\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;不是代表";
            }
            $delpartner = $partner->delegate;
            if ($option->status_required == 'oVerified' && !in_array($delpartner->status, ['paid', 'oVerified', 'unpaid']))   // 排除未通过审核搭档
            {
                $notes = "{\"reason\":\"搭档$partner_name" . "的报名未通过审核\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;未通过审核";
            }
            if ($option->status_required == 'paid' && $delpartner->status!='paid')   // 排除未通过审核搭档
            {
                $notes = "{\"reason\":\"搭档$partner_name" . "的报名未通过审核\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;未缴费";
            }
            if ($delpartner->committee != $this->committee) //continue;          // 排除非本委员会搭档
            {
                $notes = "{\"reason\":\"$partner_name" . "与$myname" . "并非同一委员会\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;不同委员会";
            }
            $delpartner_name = json_decode($partner->reginfo)->conference->partnername;
            if (empty($delpartner_name))                               // TODO: 如果对方未填搭档，自动补全
            {
                if ($option->one_empty == 'autofill')
                {
                    $delpartner_name = $myname;
                    $partner->updateInfo('conference.partnername', $myname);
                }
                else
                {
                    $notes = "{\"reason\":\"$partner_name" . "未填写搭档姓名\"}";
                    $this->reg->addEvent('partner_auto_fail', $notes);
                    return "$myname &#09;$partner->id &#09;搭档姓名$partner_name&#09;对方未填写搭档姓名";
                }
            }
            if ($delpartner_name != $myname) //continue;                 // 排除多角搭档
            {
                $notes = "{\"reason\":\"$partner_name" . "申报的搭档并非$myname" . "本人\"}";
                $this->reg->addEvent('partner_auto_fail', $notes);
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;多角搭档";
            }
            $this->partner_reg_id = $partner->id;
            $this->save();
            $this->reg->addEvent('partner_auto_success', '');
            $delpartner->partner_reg_id = $this->reg_id;
            $delpartner->save();
            return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;成功";
        }
        return $this->user()->name . "&#09;0000&#09;未填写搭档姓名";
    }

    public function assignPartnerByRid($rid, $admin = false)
    {
        $reg = Delegate::findOrFail($rid);
        if (!empty($reg->partner_reg_id))
            return "目标已有室友分配！";
        $this->partner_reg_id = $reg->reg_id;
        $name = $reg->user()->name;
        $this->save();
        if ($admin == true)
            $this->reg->addEvent('partner_submitted', '{"name":"'.Auth::user()->name."\",\"partner\":\"$name\"}");
        else
            $this->reg->addEvent('partner_manual_success', '');
        $reg->partner_reg_id = $this->reg_id;
        $name = $this->user()->name;
        $reg->save();
        if ($admin == true)
            $reg->reg->addEvent('partner_submitted', '{"name":"'.Auth::user()->name."\",\"partner\":\"$name\"}");
        else
            $reg->reg->addEvent('partner_manual_success', '');
        return "success";
    }

    public function assignPartnerByCode($id)
    {
        $rid = DB::table('linking_codes')->where('id', $id)->where('type', 'partner')->pluck('reg_id');
        if ($rid->count() == 0)
            return "配对码错误！";
        $reg = Delegate::findOrFail($rid[0]);
        if ($reg->partner_reg_id == $this->reg->id)
            return "您已与目标配对！";
        if (!empty($reg->partner_reg_id) || !empty($this->partner_reg_id))
        {
            $partners_reg = Delegate::whereIn('reg_id', [$reg->partner_reg_id, $this->partner_reg_id])->get();
            foreach ($partners_reg as $partner)
            {
                $partner->partner_reg_id = null;
                $partner->save();
            }
            $reg->partner_reg_id = null;
            $reg->save();
            $this->partner_reg_id = null;
            $this->save();
        }
        $result = $reg->assignPartnerByRid($this->reg_id);
        if ($result == 'success')
            DB::table('linking_codes')->where('id', $id)->where('type', 'partner')->delete();
        return $result;
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
        $com = $this->committee;
        $documents = $com->documents;
        if (isset($documents))
        {
            foreach ($documents as $document)
                $result->push($document);
        }
        while (isset($com->father_committee_id))
        {
            $com = $com->parentCommittee;
            $documents = $com->documents;
            if (isset($documents))
            {
                foreach ($documents as $document)
                    $result->push($document);
            }
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

    public function scopeDelegateGroup($useShortName = false, $maxDisplay = 0, $html = false)
    {
        $prefix = '';
        $scope = '';
        $i = 0;
        $n = $this->delegategroups->count();
        if ($n == 0) return '无';
        $delegategroups = $this->delegategroups;
        $htmlContent = '';
        $addScope = true;
        if (isset($this->delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                if ($useShortName)
                    $addStr = $prefix . $delegategroup->name;
                else
                    $addStr = $prefix . $delegategroup->display_name;
                if ($addScope)
                    $scope .= $addStr;
                if ($html)
                    $htmlContent .= $addStr;
                if ($maxDisplay > 0 && ++$i >= $maxDisplay)
                {
                    if ($html)
                        $addScope = false;
                    else
                        break;
                }
                $prefix = ($html ? '<br>' : ', ');
            }
        }
        if ($maxDisplay > 0 && $n > $maxDisplay) $scope .= "等 ".$n." 个";
        else if ($maxDisplay == 0) $scope = $n.'个代表组';
        if ($html)
        {
            if ($maxDisplay == 0) $scope = '<i class="fa fa-users"></i> '.$n;
            return  "<a style='cursor: pointer;' class='details-popover' data-html='1' data-placement='right' data-trigger='click' data-original-title='代表组 - ".$this->reg->name()."' data-toggle='popover' data-content='".$htmlContent."'>".$scope."</a>";
        }
        return $scope;
    }

    public function nationName($html = false)
    {
        $explain = false;
        $nations = null;
        if (is_object($this->nation))
        {
            if (!$this->seat_locked)
            {
                if ($html)
                    $result = "<i class='fa fa-unlock' aria-hidden='true'></i><div style='display:none'>未锁定</div>";
                else
                    $result = '(未锁定)';
                $nations = $this->assignedNations;
                if ($nations->count() > 1)
                    $explain = true;
            }
            else
                $result = '';
            $result .= $this->nation->displayName();
        }
        else
        {
            $nations = $this->assignedNations;
            if ($nations->count() > 0)
            {
                if ($nations->where('status', 'open')->count() > 0)
                    $result = '待选';
                else
                    $result = '无可选';
                $explain = true;
            }
            else
                $result = '待分配';
        }
        if ($explain)
        {
            $explain = ' (<i class="fa fa-wheelchair"></i> '.$nations->count().')<div style="display:none">已分配</div>';
            if ($html)
            {
                $htmlContent = '';
                $prefix = '';
                foreach ($nations as $nation)
                {
                    $htmlContent .= $prefix . $nation->displayName();
                    $prefix = '<br>';
                }
                $explain =   "<a style='cursor: pointer;' class='details-popover' data-html='1' data-placement='right' data-trigger='click' data-original-title='可选席位 - ".$this->reg->name()."' data-toggle='popover' data-content='".$htmlContent."'>".$explain."</a>";
            }
            $result .= $explain;
        }
        return $result;
    }

    public function hasRegAssignment()
    {
        $a = $this->assignments()->where('reg_assignment', 1);
        if ($a->count() == 0) return 0;
        $arr = [];
        foreach ($a as $item)
            array_push($arr, $item->id);
        $b = Assignment::whereIn('id', $arr)->whereDoesntHave('handins', function ($query) {
            $query->where('reg_id', $this->reg->id);
        })->count();
        return $b;
    }
}
