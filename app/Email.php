<?php

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
        $this->receiverArray = ['address' => 'adamxuanyi@163.com', 'name' => $user->name];
        $this->receiver = json_encode($this->receiverArray);
    }

    public function send()
    {
        $this->content = '尊敬的'.$this->receiverArray['name'].'，您好！<br><br>'.$this->content;
        Mail::to($this->receiverArray['address'], $this->receiverArray['name'])->send(new GeneralMail($this));
    }
}
