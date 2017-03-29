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

class InterviewController extends Controller
{
    public function interviews($id = 0)
    {
        if (!Reg::current()->can('edit-interviews'))
            return view('error', ['message' => '您没有面试官身份，无权进行该操作！']);
        $interviews = new Collection;
        if ($id == -1)
        {
            if (!Reg::current()->can('view-all-interviews'))
                return view('error', ['message' => '您没有权限进行该操作！']);
            $interviews = Interview::where('conference_id', Reg::currentConferenceID())->get();
        }
        elseif ($id == 0)
            $interviews = Interview::where('interviewer_id', Reg::currentID())->get();
        else
        {
            $reg = Interviewer::find($id);
            if (is_null($reg))
                return view('error', ['message' => '此人不是您所在会议的面试官！']);
            $interviews = Interview::where('interviewer_id', $id)->get();
        }
        return view('interviewList', ['interviews' => $interviews, 'iid' => $id]);
    }

    public function assignInterview(Request $request, $id)
    {
        //To-Do: permission check
        //To-Do: status check
        $interviewer = Interviewer::findOrFail($request->interviewer);
        $interview = new Interview;
        $interview->conference_id = Reg::currentConferenceID();
        $interview->reg_id = $id;
        $interview->interviewer_id = $interviewer->reg_id;
        $interview->status = 'assigned';
        $interview->save();
        Reg::findOrFail($id)->addEvent('interview_assigned', '{"interviewer":"'.$interviewer->reg->user->name.'"}');
        return redirect('/regManage?initialReg='.$id);
    }

    public function exemptInterview(Request $request, $id)
    {
        //To-Do: permission check
        //To-Do: status check
        $interviewer = Interviewer::findOrFail($request->interviewer);
        $interview = new Interview;
        $interview->conference_id = Reg::currentConferenceID();
        $interview->reg_id = $id;
        $interview->interviewer_id = $interviewer->reg_id;
        $interview->status = 'exempted';
        $interview->save();
        Reg::findOrFail($id)->addEvent('interview_exempted', '{"interviewadmin":"'.Auth::user()->name.'","interviewer":"'.$interviewer->reg->user->name.'"}');
        return redirect('/regManage?initialReg='.$id);
    }
}
