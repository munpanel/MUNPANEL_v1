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
