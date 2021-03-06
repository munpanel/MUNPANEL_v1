<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orgteam extends Model
{
    protected $table = 'ot_info';
    protected $primaryKey = 'reg_id';
    protected $fillable = ['reg_id', 'conference_id', 'school_id', 'position'];

    public function conference() {
        return $this->belongsTo('App\Conference');
    }

    public function reg()
    {
        return $this->belongsTo('App\reg');
    }

    public function school()
    {
        return $this->belongsTo('App\School');
    }

    public function regText() {
        return '组织团队（'.$this->position.'）';
    }

    public function scopeRoles()
    {
        $prefix = '';
        $scope = '';
        $i = 0;
        $n = $this->reg->roles()->count();
        if ($n == 0) return '无';
        if (null !== ($this->reg->roles))
        {
            $roles = $this->reg->roles;
            foreach($roles as $role)
            {
                $scope .= $prefix . $role->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }

    public function nextStatus() {
        switch ($this->status) { //To-Do: configurable
            case null: return 'sVerified';
            case 'sVerified': return 'oVerified';
            case 'oVerified': return 'success';
        }
    }

    public function statusText() {
        switch($this->status)
        {
            case 'reg': return '等待完成申请测试';
            case 'sVerified': return '等待组织团队审核';
            case 'oVerified':  return '申请测试审核已通过';
            case 'success': return '报名成功';
            case 'fail': return '审核未通过';
            default: return '未知状态';
        }
    }
}
