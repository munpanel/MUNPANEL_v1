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

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Note;
use App\Reg;
use App\User;
use App\Email;

class NoteController extends Controller
{
    /**
     *
     */
    public function newNote(Request $request)
    {
        if (!in_array(Reg::current()->type, ['ot', 'dais', 'interviewer']))
            return 'error';
        $note = new Note;
        $reg = Reg::findOrFail($request->reg_id);
        if ($reg->conference_id != Reg::currentConferenceID())
            return "error";
        $note->reg_id = $request->reg_id;
        $note->user_id = Auth::id();
        $note->content = $request->text;
        $note->save();
        $mentioned = extract_mention($note->content);
        foreach ($mentioned as $user) {
            $url = mp_url('home?initmodal=/ot/regInfo.modal/'.$note->reg_id);
            $mail = new Email;
            $mail->id = generateID();
            $mail->conference_id = Reg::currentConferenceID();
            $mail->title = Reg::currentConference()->name.' 新的笔记提及';
            $mail->setReceiver($user);
            $mail->sender = Reg::current()->name();
            $mail->content = '在'.Reg::currentConference()->name.'中，'.Reg::current()->name().'在'.$reg->name().'的面试笔记中提到了您。该笔记内容如下：<br/><pre>'.$note->content.'</pre><br>点此查看详情：<a href='.$url.'>'.$url.'</a>';
            $mail->send();
            $mail->save();
        }
        return 'success';

        if (Reg::current()->type == 'ot')
            return redirect('/regManage?initialReg='.$request->reg_id);
        if (Reg::current()->type == 'interviewer')
            return redirect('/interviews');
        return redirect('/home');
    }

}
