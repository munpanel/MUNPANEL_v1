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

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table='nations';
    protected $fillable = ['committee_id', 'name', 'conpetence', 'veto_power', 'attendance', 'locked'];

    public function committee()
    {
        return $this->belongsTo('App\Committee');
    }

    public function delegates()
    {
        return $this->hasMany('App\Delegate');
    }

    public function assignedDelegates()
    {
        return $this->belongsToMany('App\Delegate', 'delegate_nation', 'nation_id', 'delegate_id');
    }

    public function nationgroups()
    {
        return $this->belongstoMany('App\Nationgroup', 'nationgroup_nation');
    }

    public function scopeDelegate($withBizCard = false)
    {
        $prefix = '';
        $scope = '';
        if (isset($this->delegates))
        {
            $delegates = $this->delegates;
            foreach($delegates as $delegate)
            {
                $scope .= $prefix;
//                if ($withBizCard) $scope .= '<a href="'.mp_url('/delBizCard.modal/'.$delegate->user->id).'" class="details-modal" data-toggle="ajaxModal">';
                $scope .= $delegate->reg->name();
//                if ($withBizCard) $scope .= '</a>';
                if ($withBizCard) $scope .= '<a style="cursor: pointer;" class="details-popover" data-html="1" data-placement="right" data-trigger="click" data-original-title="'.$delegate->reg->name().'" data-toggle="popover" data-content="'.view('delegateBizCard', ['delegate' => $delegate]).'"><i class="fa fa-phone-square fa-fw"></i></a>';
                $prefix = ', ';
            }
        }
        if ($scope != '')
            return $scope;
        return '无';
    }

    public function scopeAssignedDelegate($withBizCard = false)
    {
        $prefix = '';
        $scope = '';
        $delegates = $this->assignedDelegates;
        if ($delegates->count() > 0)
        {
            foreach($delegates as $delegate)
            {
                $scope .= $prefix;
//                if ($withBizCard) $scope .= '<a href="'.mp_url('/delBizCard.modal/'.$delegate->user->id).'" class="details-modal" data-toggle="ajaxModal">';
                $scope .= $delegate->reg->name();
//                if ($withBizCard) $scope .= '</a>';
                if ($withBizCard) $scope .= '<a style="cursor: pointer;" class="details-popover" data-html="1" data-placement="right" data-trigger="click" data-original-title="'.$delegate->reg->name().'" data-toggle="popover" data-content="'.view('delegateBizCard', ['delegate' => $delegate]).'"><i class="fa fa-phone-square fa-fw"></i></a>';
                $prefix = ', ';
            }
        }
        if ($scope != '')
            return $scope;
        return '无';
    }

    public function scopeNationGroup($useShortName = false, $maxDisplay = 0)
    {
        $prefix = '';
        $scope = '';
        $i = 0;
        $n = $this->nationgroups->count();
        if ($n == 0) return '无';
        if (isset($this->nationgroups))
        {
            $nationgroups = $this->nationgroups;
            foreach($nationgroups as $nationgroup)
            {
                if ($useShortName)
                    $scope .= $prefix . $nationgroup->name;
                else
                    $scope .= $prefix . $nationgroup->display_name;
                if ($maxDisplay > 0 && ++$i >= $maxDisplay) break;
                $prefix = ', ';
            }
        }
        if ($maxDisplay > 0 && $n > $maxDisplay) $scope .= "等 ".$n." 个";
        return $scope;
    }

    public function displayName($remark = true, $committee = 0)
    {
        $info = '';
        if ($remark)
            $info .= $this->remark;
        if ($committee == 1) {
            if ($info != '')
                $info .= ', ';
            $info .= $this->committee->name;
        }
        else if ($committee == 2) {
            if ($info != '')
                $info .= ', ';
            $info .= $this->committee->display_name;
        }
        if ($info != '')
            return $this->name . ' (' . $info . ')';
        return $this->name;
    }

    // deprecated due to change of logic.
    public function setLock($lock = true)
    {
        if ($lock)//Set lock of delegates first before calling this function. This design helps avoiding bugs.
        {
            $this->locked = true;
            $this->save();
            $delegates = $this->assignedDelegates;
            foreach ($delegates as $delegate)
            {
                if (!$delegate->seat_locked)
                {
                    $this->assignedDelegates()->detach($delegate);
                    $sms = '感谢您报名'.Reg::currentConference()->name.'，由于其他代表的席位锁定，系统自动更新了您的可选席位列表，敬请留意。';
                    if ($delegate->nation_id == $this->id)
                    {
                        $delegate->nation_id = null;
                        $delegate->save();
                        $sms = '感谢您参加'.Reg::currentConference()->name.'，很遗憾您之前选择的席位已被他人锁定。请您重新登录系统选择席位，感谢您的理解与支持。';
                    }
                    $delegate->reg->user->sendSMS($sms);
                }
            }
        }
        else
        {
            $this->locked = false;
            $this->save();
            $delegates = $this->assignedDelegates;
            foreach ($delegates as $delegate)
            {
                if ($delegate->seat_locked)
                {
                    $delegate->seat_locked = false;
                    $delegate->save();
                    $delegate->reg->user->sendSMS('感谢您报名'.Reg::currentConference()->name.'，您的席位锁定状态已被取消。您现可登录系统修改您的席位选择，感谢。');
                }
            }
        }
    }
}
