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

namespace App;

use App\User;
use App\Mail\GeneralMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Email extends Model
{
    public $incrementing = false;
    protected $guarded = [];
    private $receiverArray;

    public function setReceiver($user)
    {
        $this->receiverArray = ['address' => $user->email, 'name' => $user->name];
        $this->receiver = json_encode($this->receiverArray);
    }

    public function send()
    {
        if (is_null($this->receiverArray['address']))
            $this->receiverArray = json_decode($this->receiver, true);
        $this->content = '<h1>您好，'.$this->receiverArray['name'].'</h1>'.$this->content;
        Mail::to($this->receiverArray['address'], $this->receiverArray['name'])->send(new GeneralMail($this));
    }

    public function queue()
    {
        $this->content = '<h1>您好，'.$this->receiverArray['name'].'</h1>'.$this->content;
        Mail::to($this->receiverArray['address'], $this->receiverArray['name'])->queue(new GeneralMail($this));
    }
}
