<?php

namespace App\Http\Controllers;

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

    public function __construct()
    {
        $this->pusher = App::make('pusher');
        $this->chatChannel = self::DEFAULT_CHAT_CHANNEL;
        $this->middleware('auth');
    }

    public function getIndex()
    {
        /*if(!$this->user)
        {
            return redirect('auth/github?redirect=/chat');
        }*/

        return view('chat', ['chatChannel' => $this->chatChannel]);
    }

    public function postMessage(Request $request)
    {
        $surfix = ' (';
        switch (Auth::user()->type)
        {
            case 'ot': $surfix .= '组织团队';break;
            case 'dais': $surfix .= '学术团队 - '. Auth::user()->committee->name;break;
            case 'volunteer': $surfix .= '志愿者';break;
            case 'observer': $surfix .= '观察员';break;
            case 'delegate': $surfix .= '代表 - '. Auth::user()->committee->name. (Auth::user()->committee->is_allocated ? ' ' . Auth::user()->delegate->nation->name : '');break;
        }
        $surfix .= ')';
        $message = [
            'text' => e($request->input('chat_text')),
            'username' => Auth::user()->name . $surfix,
            'avatar' =>  'https://www.gravatar.com/avatar/' . md5( strtolower( trim( Auth::user()->email ) ) ) . '?d=https://www.munpanel.com/images/avatar.jpg&s=320',
            'timestamp' => (time()*1000)
        ];
        $this->pusher->trigger($this->chatChannel, 'new-message', $message);
    }
}
