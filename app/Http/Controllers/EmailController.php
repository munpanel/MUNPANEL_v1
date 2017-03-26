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

use App\Email;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        /*
        $mail = new Email;
        $mail->id = generateID();
        $mail->conference_id = 2; //ToDo
        $mail->title = 'MUNPANEL 账号验证';
        $mail->setReceiver($this);
        $mail->sender = 'MUNPANEL Team';
        $mail->content = '感谢您使用 MUNPANEL 系统！请点击以下链接验证您的电子邮箱：<br/><pre><a href="'.$url.'">'.$url.'</a></pre>';
        $mail->send();
        $mail->save();
         */
    }

    public function showEmail($id)
    {
        $email = Email::findOrFail($id);
        return view('emailTemplate', [
            'id' => $email->id,
            'title' => $email->title,
            'content' => $email->content,
            'sender' => $email->sender,
            'receiver' => json_decode($email->receiver),
            'webView' => true,
        ]);

    }
}
