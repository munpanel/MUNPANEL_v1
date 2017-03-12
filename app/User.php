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

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use PragmaRX\Google2FA\Contracts\Google2FA;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'emailVerificationToken', 'google2fa_enabled'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function regs() {
        return $this->hasMany('App\Reg');
    }
    
    public function invoiceItems() {
        $items = array();
        if ($this->type == 'delegate')
            array_push($items, array(1, 'BJMUNC 2017 会费', 530));
        if ($this->specific()->accomodate)
            array_push($items, array(3, '二十一世纪饭店住宿费', 170));
        return $items;
    }
    
    public function invoiceAmount() {
        $items = $this->invoiceItems();
        $sum = 0;
        foreach ($items as $tmp)
        {
            $sum += $tmp[2] * $tmp[0];
        }
        return $sum;
    }

    public function sendVerificationEmail() {
        $url = secure_url('/verifyEmail/'.$this->email.'/'.$this->emailVerificationToken);
        $mail = new Email;
        $mail->id = generateID();
        $mail->conference_id = 2; //ToDo
        $mail->title = 'MUNPANEL 账号验证';
        $mail->setReceiver($this);
        $mail->sender = 'MUNPANEL Team';
        $mail->content = '感谢您使用 MUNPANEL 系统！请点击以下链接验证您的电子邮箱：<br/><pre><a href="'.$url.'">'.$url.'</a></pre>';
        $mail->send();
        $mail->save();
    }

    public function generate2FAkey() {
        $this->google2fa_secret = Google2FA::generateSecretKey(16, $this->id);
        $this->save();
        return $this->google2fa_secret;
    }

    public function generate2FAqr() {
        return Google2FA::getQRCodeInline('MUNPANEL', $this->email, $this->generate2FAkey());
    }

    public function verify2FAkey($secret) {
        return Google2FA::verifyKey($this->google2fa_secret, $secret);
    }

    public function sendSMS($message) {
        if (mb_strlen($this->name) > 5)
            $name = '模联人';
        else
            $name = $this->name;
        return \App\Http\Controllers\SmsController::send([$this->tel], '尊敬的'.$name.'，'.$message);
    }

    public function verified() {
        if ($this->emailVerificationToken != 'success')
            return false;
        if ($this->telVerifications != -1) //3/2/1: tries left; -1: activated
            return false;
        return true;
    }

}
