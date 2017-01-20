<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Volunteer extends Model
{
    protected $table='volunteer_info';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id','school_id','status','gender','sfz','grade','email','qq','wechat','parenttel','tel','accomodate','roommatename','roommate_user_id','notes'];

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function school() {
        return $this->belongsTo('App\School');
    }
    
    public function assignroommateByName() 
    {
        if (!$this->accomodate) return $this->user->name . "&#09;未申请住宿";
        $this->roommate_user_id = null;
        if (isset($this->roommatename))
        {
            $roommate_name = $this->roommatename;
            $myname = $this->user->name;
            // 对于带空格的roommatename值，在此if表达式外增加foreach表达式以逐一处理
            $roommates = User::where('name', $roommate_name);
            $count = $roommates->count();
            if ($count == 0) 
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "未找到$roommate_name" . "的报名记录！";
                $this->save();
                return $myname . "&#09;&#09;室友姓名$roommate_name&#09;未找到室友的报名记录";
            }
            $roommate = $roommates->first();
            if ($count > 1)
            {
                foreach ($roommates as $roommate1)
                {
                    if ($roommate->type == 'unregistered') continue;                    // 排除未注册室友
                    $roommate = $roommate1;
                    break;
                }
            }
            if ($roommate->type == 'unregistered')                                      // 排除未注册室友
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$roommate_name" . "并未报名！";
                $this->save();
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;未报名参会"; 
            }
            $typedroommate = $roommate->specific();
            if (is_null($typedroommate->roommatename))                          // 如果对方未填室友，自动补全
                $typedroommate->roommatename = $myname;
            if ($typedroommate->roommatename != $myname) //continue;}           // 排除多角室友
            {
                $this->notes .= "$roommate_name" . "申报的室友并非$myname" . "本人！";
                $this->save(); 
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;多角室友";
            }
            if ($typedroommate->gender != $this->gender)                        // 排除男女混宿
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "室友$roommate_name" . "与报名者为异性！";
                $this->save();
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;室友为异性";
            }
            $this->roommate_user_id = $roommate->id;       
            $this->save();
            $typedroommate->roommate_user_id = $this->user->id;
            $typedroommate->save();
            return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;成功";
        }
    }
    
    public function roommate() {
        return $this->hasOne('App\User', 'roommate_user_id'); 
    }
}
