<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $table='delegate_info';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id','school_id','status','gender','sfz','grade','email','qq','wechat','partnername','parenttel','tel','committee_id','accomodate','roommatename'];

    public function committee() {
        return $this->belongsTo('App\Committee');
    }

    public function nation() {
        return $this->belongsTo('App\Nation');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }

    public function individual_assignments() {
        return $this->hasMany('App\Assignment');
    }

    public function nationgroups() {
        return $this->nation->nationgroups();
    }

    public function delegategroups() {
        return $this->belongstoMany('App\Delegategroup');
    }

    public function assignments() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $assignments = $nationgroup->assignments;
                    if (isset($assignments))
                        foreach ($assignments as $assignment)
                            $result->push($assignment);
                }
            }
        }
        $assignments = $this->committee->assignments;
        if (isset($assignments))
        {
            foreach ($assignments as $assignment)
                $result->push($assignment);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $assignments = $delegategroup->assignments;
                if (isset($assignments))
                    foreach ($assignments as $assignment)
                        $result->push($assignment);
            }
        }
        return $result->unique()->sortBy('id');
    }
    
    public function assignPartnerByName() 
    {
        $this->partner_user_id = null;
        if (isset($this->partnername)
        {
            $partner_name = $this->partnername;
            $partners = User::where('name', $partner_name);
            if (!is_null($partners)) // 对于带空格的partnername值，在此if表达式外增加foreach表达式以逐一处理
            {
                foreach ($partners as $partner)
                {
                    if ($partner->type != 'delegate') continue;                               // 排除非代表搭档
                    if ($partner->delegate->committee != $this->committee) continue;          // 排除非本委员会搭档
                    if (is_null($partner->delegate->partnername))                             // 如果对方未填搭档，自动补全
                        $partner->delegate->partnername = $this->user->name;
                    if ($partner->delegate->partnername != $this->user->name) continue;       // 排除多角搭档
                    $this->partner_user_id = $partner->id;
                    $partner->delegate->partner_user_id = $this->user->id;
                    return;
                }
            }
	    if (isset($this->notes)) $this->notes .= "\n"
            $this->notes .= "在自动配对搭档时发生错误，请核查";
            return;
        }
    }
    
    public function partner() {
        return $this->hasOne('App\User', 'foreign_key')->delegate; // TODO: 确定这里的外键名称
    }
    
    public function assignroommateByName() 
    {
        $this->roommate_user_id = null;
        if (isset($this->roommatename)
        {
            $roommate_name = $this->roommatename;
            $roommates = User::where('name', $roommate_name);
            if (!is_null($roommates)) // 对于带空格的roommatename值，在此if表达式外增加foreach表达式以逐一处理
            {
                foreach ($roommates as $roommate)
                {
                    if ($roommate->type == 'unregistered') continue;                           // 排除未注册室友
                    if (is_null($roommate->specific()->roommatename))                          // 如果对方未填室友，自动补全
                        $roommate->specific()->roommatename = $this->user->name;
                    if ($roommate->specific()->roommatename != $this->user->name) continue;    // 排除多角室友
                    if ($roommate->specific()->gender != $this->gender)                        // 排除男女混宿
                    {
	            if (isset($this->notes)) $this->notes .= "\n"
                        $this->notes .= "在自动配对室友时检测到室友为异性，请核查";
                        return;
                    }
                    $this->roommate_user_id = $roommate->id;
                    $roommate->specific()->roommate_user_id = $this->user->id;
                    return;
                }
            }
	    if (isset($this->notes)) $this->notes .= "\n"
            $this->notes .= "在自动配对室友时发生错误，请核查";
            return;
        }
    }
    
    public function roommate() {
        return $this->hasOne('App\User', 'foreign_key')->specific(); // TODO: 确定这里的外键名称
    }
}
