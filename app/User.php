<?php
/**
 * Copyright (C) MUNPANEL
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

    public function schools() {
        return $this->belongsToMany('App\School');
    }

    public function sendVerificationEmail() {
        $url = mp_url('/verifyEmail/'.$this->email.'/'.$this->emailVerificationToken);
        $mail = new Email;
        $mail->id = generateID();
        $mail->conference_id = Reg::currentConferenceID();
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

    public function identityHTML() {
        if ($this->relationLoaded('regs'))
            $regs = $this->regs->where('conference_id', Reg::currentConferenceID());
        else
            $regs = $this->regs()->where('conference_id', Reg::currentConferenceID())->get();
        $count = $regs->count();
        if ($count == 0)
            return "无任何身份";
        if ($count == 1)
            return $regs->first()->regText();
        $result =  "<a style='cursor: pointer;' class='details-popover' data-html='1' data-placement='right' data-trigger='click' data-original-title='".$this->name."' data-toggle='popover' data-content='".$this->identityText()."'>".$count."项身份</a>";
        return $result;
    }

    public function identityText() {
        if ($this->relationLoaded('regs'))
            $regs = $this->regs->where('conference_id', Reg::currentConferenceID());
        else
            $regs = $this->regs()->where('conference_id', Reg::currentConferenceID())->get();
        //$regs = $regs->where('conference_id', Reg::currentConferenceID())->get();
        $result = "";
        $prefix = "";
        foreach ($regs as $reg)
        {
            $result .= $prefix.$reg->regText();
            $prefix = "、";
        }
        return $result;
    }

}
