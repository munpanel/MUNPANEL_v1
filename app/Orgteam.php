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
        if (null !== ($this->reg->roles()))
        {
            $roles = $this->reg->roles();
            foreach($roles as $role)
            {
                $scope .= $prefix . $role->display_name;
                $prefix = ', ';
            }
        }
        return $scope;
    }
}
