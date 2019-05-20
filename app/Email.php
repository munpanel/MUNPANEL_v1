<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use App\User;
use App\Mail\GeneralMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class Email extends Model
{
    public $incrementing = false;
    protected $guarded = [];
    private $receiverArray;
    private $plainContent;

    public function setReceiver($user)
    {
        $this->receiverArray = ['address' => $user->email, 'name' => $user->name];
        $this->receiver = json_encode($this->receiverArray);
    }

    public function send()
    {
        if (is_null($this->receiverArray['address']))
            $this->receiverArray = json_decode($this->receiver, true);
        $this->plainContent = '您好，'.$this->receiverArray['name'].'：'.$this->content;
        $this->content = '<h1>您好，'.$this->receiverArray['name'].'</h1>'.$this->content;
        //Mail::to('adamxuanyi@163.com', $this->receiverArray['name'])->send(new GeneralMail($this));
        Mail::to($this->receiverArray['address'], $this->receiverArray['name'])->send(new GeneralMail($this));
        Log::info('Sent an email (ID: '.$this->id.') to '.$this->receiverArray['address'].' '.$this->receiverArray['name']);
    }

    public function queue()
    {
        $this->plainContent = '您好，'.$this->receiverArray['name'].'：'.$this->content;
        $this->content = '<h1>您好，'.$this->receiverArray['name'].'</h1>'.$this->content;
        Mail::to($this->receiverArray['address'], $this->receiverArray['name'])->queue(new GeneralMail($this));
    }
}
