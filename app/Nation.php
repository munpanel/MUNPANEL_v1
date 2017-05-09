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

use Illuminate\Database\Eloquent\Model;

class Nation extends Model
{
    protected $table='nations';
    protected $fillable = ['committee_id', 'name', 'conpetence', 'veto_power', 'attendance'];

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
}
