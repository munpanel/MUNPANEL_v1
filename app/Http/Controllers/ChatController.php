<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Controllers;

use App\Reg;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    var $pusher;
    var $chatChannel;

    const DEFAULT_CHAT_CHANNEL = 'chat';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
        $this->middleware('auth');
    }
    /**
     * Show the chat site.
     *
     * @return \Illuminate\Http\Response
     */
    public function getIndex()
    {
        /*if(!$this->user)
        {
            return redirect('auth/github?redirect=/chat');
        }*/

        return view('chat', ['chatChannel' => $this->chatChannel]);
    }

    /**
     * Post a message to chat.
     *
     * @param Request $request
     * @return void
     */
    public function postMessage(Request $request)
    {
        $surfix = ' (';
        switch (Reg::current()->type)
        {
            case 'ot': $surfix .= '组织团队';break;
            case 'dais': $surfix .= '学术团队 - '. Reg::current()->committee->name;break;
            case 'volunteer': $surfix .= '志愿者';break;
            case 'observer': $surfix .= '观察员';break;
            case 'delegate': $surfix .= '代表 - '. Reg::current()->committee->name. (Reg::current()->committee->is_allocated ? ' ' . Reg::current()->delegate->nation->name : '');break;
        }
        $surfix .= ')';
        $message = [
            'text' => e($request->input('chat_text')),
            'username' => Reg::current()->name() . $surfix,
            'avatar' =>  'https://www.gravatar.com/avatar/' . md5( strtolower( trim( Auth::user()->email ) ) ) . '?d=https://www.munpanel.com/images/avatar.jpg&s=320',
            'timestamp' => (time()*1000)
        ];
        $this->pusher->trigger($this->chatChannel, 'new-message', $message);
    }
}
