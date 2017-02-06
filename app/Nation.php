<?php

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
//                if ($withBizCard) $scope .= '<a href="'.secure_url('/delBizCard.modal/'.$delegate->user->id).'" class="details-modal" data-toggle="ajaxModal">';
                $scope .= $delegate->user->name;
//                if ($withBizCard) $scope .= '</a>';
                if ($withBizCard) $scope .= '<a style="cursor: pointer;" class="details-popover" data-html="1" data-placement="right" data-trigger="click" data-original-title="'.$delegate->user->name.'" data-toggle="popover" data-content="'.view('delegateBizCard', ['delegate' => $delegate]).'"><i class="fa fa-phone-square fa-fw"></i></a>';
                $prefix = ', ';
            }
        }
        if ($scope != '')
            return $scope;
        return '无';
    }

    public function scopeNationGroup()
    {
        $prefix = '';
        $scope = '';
        if (isset($this->nationgroups))
        {
            $nationgroups = $this->nationgroups;
            foreach($nationgroups as $nationgroup)
            {
                $scope .= $prefix . $nationgroup->display_name;
                $prefix = ', ';
            }
        }
        if ($scope != '')
            return $scope;
        return '无';
    }
}
