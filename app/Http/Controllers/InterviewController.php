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

namespace App\Http\Controllers;

use App\Reg;
use App\Delegate;
use App\Interview;
use App\Interviewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class InterviewController extends Controller
{
    public function interviews($id = 0)
    {
        if (!Reg::current()->can('view-all-interviews') && Reg::current()->type != 'interviewer')
            return view('error', ['msg' => '您没有面试官身份，无权进行该操作！']);
        $interviews = new Collection;
        if ($id == -1)
        {
            if (!Reg::current()->can('view-all-interviews'))
                return view('error', ['msg' => '您没有权限进行该操作！']);
            $interviews = Interview::where('conference_id', Reg::currentConferenceID())->get();
        }
        elseif ($id == 0 || $id == Reg::currentID())
            $interviews = Interview::where('interviewer_id', Reg::currentID())->get();
        else
        {
            if (!Reg::current()->can('view-all-interviews'))
                return view('error', ['msg' => '您没有权限进行该操作！']);
            $reg = Interviewer::find($id);
            if (is_null($reg))
                return view('error', ['msg' => '此人不是您所在会议的面试官！']);
            $interviews = Interview::where('interviewer_id', $id)->get();
        }
        return view('dais.interviewList', ['interviews' => $interviews, 'iid' => $id]);
    }

    public function interview(Request $request, $id, $action)
    {
        $interview = Interview::findOrFail($id);
        if ($interview->interviewer_id != Reg::currentID())
        {
            return view('error', ['msg' => '您没有权限进行该操作！']);
        }
        switch($action)
        {
            case "arrange":
                if ($interview->status != 'assigned')
                    return view('error', ['msg' => '状态错误！']);
                $interview->status = 'arranged';
                $interview->arranged_at = $request->arrangeTime;
                $interview->arranging_notes = $request->notes;
                $type = intval($request->typeInterview);
                $interview->save();
                $interview->reg->addEvent('interview_arranged', '{"interviewer":"'.Reg::current()->name().'","time":"'.$interview->arranged_at.'","method":"'.typeInterview($type).'"}');
                $interview->reg->user->sendSMS('感谢您以代表身份报名'.Reg::currentConference()->name.'。面试官'.Reg::current()->name().'已为您安排一场于'.date(' n 月 j 日 H:i ', strtotime($interview->arranged_at)).'进行的'.typeInterview($type).'面试。请保持联系方式畅通，预祝面试愉快。');
                break;
            case "exempt":
                if ($interview->status != 'assigned')
                    return view('error', ['msg' => '状态错误！']);
                $interview->status = 'exempted';
                $interview->finished_at = date('Y-m-d H:i:s');
                $interview->arranging_notes = $request->notes;
                $interview->save();
                $interview->reg->addEvent('interview_exempted', '{"interviewadmin":"'.Reg::current()->name().'","interviewer":"并"}');
                $interview->reg->user->sendSMS('感谢您参加'.Reg::currentConference()->name.'，面试官'.Reg::current()->name().'已免试通过了您的面试。请静候席位分配，感谢。');
                break;
            case "rollBack":
                if ($interview->status != 'assigned')
                    return view('error', ['msg' => '状态错误！']);
                $interview->status = 'cancelled';
                $interview->finished_at = date('Y-m-d H:i:s');
                $interview->arranging_notes = $request->notes;
                $interview->save();
                $interview->reg->addEvent('interview_cancelled', '{"interviewer":"'.Reg::current()->name().'"}');
                break;
            case "cancel":
                if ($interview->status != 'arranged')
                    return view('error', ['msg' => '状态错误！']);
                $interview->status = 'cancelled';
                $interview->finished_at = date('Y-m-d H:i:s');
                $interview->arranging_notes = $request->notes;
                $interview->save();
                $newInterview = new Interview;
                $newInterview->conference_id = Reg::currentConferenceID();
                $newInterview->reg_id = $interview->reg_id;
                $newInterview->interviewer_id = Reg::currentID();
                $newInterview->status = 'assigned';
                $newInterview->save();
                $interview->reg->addEvent('interview_cancelled', '{"interviewer":"'.Reg::current()->name().'"}');
                break;
            case "rate":
                if ($interview->status != 'arranged' && $interview->status != 'assigned')
                    return view('error', ['msg' => '状态错误！']);
                $interview->status = $request->result . 'ed';
                $interview->finished_at = date('Y-m-d H:i:s');
                $scores = array();
                $score = 0;
                $scoresOptions = json_decode(Reg::currentConference()->option('interview_scores'));
                foreach($scoresOptions->criteria as $key => $value)
                {
                    $scores[$key] = $request->$key;
                    $score += intval($request->$key) * $value->weight;
                }
                $score *= $scoresOptions->total / 5;
                $interview->scores = json_encode($scores);
                $interview->score = round($score, 1);
                $interview->public_fb = $request->public_fb;
                $interview->internal_fb = $request->internal_fb;
                $interview->save();
                $interview->reg->addEvent('interview_' . $interview->status, '{"interviewer":"'.Reg::current()->name().'"}');
                if ($interview->status == 'passed')
                    $interview->reg->user->sendSMS('感谢您参加'.Reg::currentConference()->name.'，面试官'.Reg::current()->name().'已通过了您的面试。请静候席位分配，感谢。');
                else
                    $interview->reg->user->sendSMS('感谢您参加'.Reg::currentConference()->name.'，我们很遗憾地通知您，面试官'.Reg::current()->name().'并未通过您的面试。');
                break;
            case "arrangeModal":
                return view('interviewer.arrangeModal', ['id' => $id]);
            case "exemptModal":
                return view('interviewer.exemptModal', ['id' => $id, 'mode' => 'exempt']);
            case "rollBackModal":
                return view('interviewer.exemptModal', ['id' => $id, 'mode' => 'rollBack']);
            case "cancelModal":
                return view('interviewer.exemptModal', ['id' => $id, 'mode' => 'cancel']);
            case "rateModal":
                return view('interviewer.rateModal', ['id' => $id, 'scoresOptions' => json_decode(Reg::currentConference()->option('interview_scores'))]);
            default:
                return view('error', ['msg' => '指令无效！']);
        }
        return redirect(mp_url('/interviews')); //blablabla
    }

    public function assignInterview(Request $request, $id)
    {
        //To-Do: permission check
        $reg = Reg::findOrFail($id);
        if (!in_array($reg->specific()->realStatus(), ['interview_unassigned', 'interview_passed', 'interview_failed', 'interview_retest_unassigned', 'interview_retest_passed', 'interview_retest_failed']))
            return view('error', ['msg' => '此代表已被分配面试，不能执行该操作！']);
        $interviewer = Interviewer::findOrFail($request->interviewer);
        if (!empty($request->moveCommittee) && $reg->delegate->committee_id != $interviewer->committee_id)
        {
            $delegate = $reg->delegate;
            $delegate->committee_id = $interviewer->committee_id;
            $delegate->save();
            $reg->addEvent('committee_moved', '{"name":"'.Reg::current()->name().'","committee":"'.$interviewer->committee->name.'"}');
        }
        $interview = new Interview;
        $interview->conference_id = Reg::currentConferenceID();
        $interview->reg_id = $id;
        if (!empty($request->isRetest))
            $interview->retest = true;
        $interview->interviewer_id = $interviewer->reg_id;
        $interview->arranging_notes = $request->notes;
        $interview->status = 'assigned';
        $interview->save();
        $interview->reg->addEvent('interview_assigned', '{"interviewer":"'.$interviewer->reg->user->name.'"}');
        $interview->reg->user->sendSMS('感谢您以代表身份报名'.Reg::currentConference()->name.'。现已为您分配面试官'.$interviewer->reg->name().'，登录系统查看详情。请保持联系方式畅通，预祝面试愉快。');
        $interviewer->reg->user->sendSMS(Reg::current()->name().'已将'.Reg::findOrFail($id)->user->name.'分配给您进行面试，请及时登陆系统联系代表并安排面试时间，感谢您使用 MUNPANEL 系统。');
        return redirect('/regManage?initialReg='.$id);
    }

    public function exemptInterview(Request $request, $id)
    {
        //To-Do: permission check
        $reg = Reg::findOrFail($id);
        if ($reg->specific()->realStatus() != 'interview_unassigned')
            return view('error', ['msg' => '此代表已被分配面试，不能执行该操作！']);
        $interviewer = Interviewer::findOrFail($request->interviewer);
        $interview = new Interview;
        $interview->conference_id = Reg::currentConferenceID();
        $interview->reg_id = $id;
        $interview->interviewer_id = $interviewer->reg_id;
        $interview->arranging_notes = $request->notes;
        $interview->status = 'exempted';
        $interview->save();
        $interview->reg->addEvent('interview_exempted', '{"interviewadmin":"'.Reg::current()->name().'","interviewer":"'.$interviewer->reg->user->name.'"}');
        $interview->reg->user->sendSMS('感谢您参加'.Reg::currentConference()->name.'，面试官'.Reg::current()->name().'已免试通过了您的面试。请静候席位分配，感谢。');
        return redirect('/regManage?initialReg='.$id);
    }

    public function findInterviewerModal()
    {
        return view('interviewer.findInterviewerModal');
    }

    public function gotoInterviewer(Request $request)
    {
        return redirect(mp_url('/interviews/'.$request->interviewer));
    }
}
