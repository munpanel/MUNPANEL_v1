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

use Illuminate\Http\Request;
use App\Note;
use App\Reg;
use App\User;

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
        $note->reg_id = $request->reg_id;
        $note->user_id = Auth::id();
        $note->content = $request->text;
        $note->save();
        if (Reg::current()->type == 'ot')
            return redirect('/regManage?initialReg='.$request->reg_id);
        if (Reg::current()->type == 'interviewer')
            return redirect('/interviews');
        return redirect('/home');
    }

}
