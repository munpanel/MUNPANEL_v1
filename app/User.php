<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function school() {
        return $this->hasOne('App\School');
    }

    public function delegate() {
        return $this->hasOne('App\Delegate');
    }

    public function volunteer() {
        return $this->hasOne('App\Volunteer');
    }

    public function dais() {
        return $this->hasOne('App\Dais');
    }

    public function observer() {
        return $this->hasOne('App\Observer');
    }

    public function committee() {
        return $this->specific()->belongsTo('App\Committee');
    }

    public function orders() {
        return $this->hasMany('App\Order');
    }

    public function regs() {
        return $this->hasMany('App\Reg');
    }
    
    public function specific() {
        if ($this->type == 'delegate')
            return $this->delegate;
        else if ($this->type == 'volunteer')
            return $this->volunteer;
        else if ($this->type == 'observer')
            return $this->observer;
        else if ($this->type == 'dais')
            return $this->dais;
        else
            return null;
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
}
