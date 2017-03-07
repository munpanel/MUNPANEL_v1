<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reg extends Model
{
    protected $fillable = ['user_id','conference_id','school_id','type','enabled','gender','reginfo','accomodate','roommate_user_id'];    
    
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['password'];

    public function conference() {
        return $this->belongsTo('App\Conference');
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

    public function cards() {
        return $this->hasMany('App\Card', 'reg_id');
    }

    public function events() {
        return $this->hasMany('App\Event');
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
    
    public function ot() {        
        return $this->hasOne('App\Orgteam');
    }

    public function specific() {
        return $this->{$this->type};
    }
    
    public function roommate() {
        return $this->belongsTo('App\User', 'roommate_user_id'); 
    }

    public function interviews()
    {
        return $this->hasMany('App\Interview');
    }

    public function addEvent($type, $content)
    {
        $event = new Event;
	    $event->eventtype_id = $type;
	    $event->content = $content;
	    $event->reg_id = $this->id;
	    $event->save();
	    return $event;
    }
    
    public function make()
    {
        switch ($this->type)
        {
            case 'delegate':
                $delegate = new Delegate();
                $delegate->reg_id = $this->id;
                $delegate->conference_id = $this->conference_id;
                $delegate->school_id = $this->school_id;
                $delegate->committee_id = json_decode($this->reginfo)->conference->committee;
                $delegate->save();
                break;
            case 'observer':
                $observer = new Observer();
                $observer->reg_id = $this->id;
                $observer->conference_id = $this->conference_id;
                $delegate->school_id = $this->school_id;
                $observer->committee_id = json_decode($this->reginfo)->conference->committee;
                $observer->save();
                break;
            case 'volunteer':
                $volunteer = new Volunteer();
                $volunteer->reg_id = $this->id;
                $volunteer->conference_id = $this->conference_id;
                $delegate->school_id = $this->school_id;
                $volunteer->save();
                break;
        }
    }
    
    public function assignRoommateByName() 
    {
        // TODO: 重写所有的 roommatename
        if (!$this->accomodate) return $this->user->name . "&#09;0&#09;未申请住宿";
        $this->roommate_user_id = null;
        if (isset($this->roommatename))
        {
            $roommate_name = $this->roommatename;
            $myname = $this->user->name;
            // 对于带空格的roommatename值，在此if表达式外增加foreach表达式以逐一处理
            // TODO: 重写以下 1 行
            $roommates = Reg::where('name', $roommate_name);
            $count = $roommates->count();
            if ($count == 0) 
            {
                $notes = "{'reason':'未找到室友$roommate_name" . "的报名记录'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname . "&#09;0&#09;室友姓名$roommate_name&#09;未找到室友的报名记录";
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
            if ($roommate->id == $this->user->id)                               // 排除自我配对
            {
                $notes = "{'reason':'$myname" . "申报的室友与报名者本人重合'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname  ."&#09;".$roommate->id . "&#09;自我配对";
            }
            /*
            if ($roommate->type == 'unregistered')                              // 排除未注册室友
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$roommate_name" . "并未报名！";
                $this->save();
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;未报名参会"; 
            }
            if ($roommate->type != 'delegate' && $roommate->type != 'volunteer' )
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$roommate_name" . "组委/学团";
                $this->save();
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;组委/学团"; 
            }
            */
            $typedroommate = $roommate->specific();
            if ($typedroommate->status != 'paid' && $typedroommate->status != 'oVerified')   // 排除未通过审核室友
            {
                $notes = "{'reason':'室友$roommate_name" . "的报名未通过审核'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;未通过审核";
            }
            if ($roommate->accomodate)                                    // 排除对方未申请住宿
            {
                $notes = "{'reason':'$roommate_name" . "未申请住宿'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;未申请住宿";
            }
            if (is_null($roommate->roommatename))                          // 如果对方未填室友，自动补全
                $roommate->roommatename = $myname;
            if ($roommate->roommatename != $myname) //continue;}           // 排除多角室友
            {
                $notes = "{'reason':'$roommate_name" . "申报的室友并非$myname" . "本人'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;多角室友";
            }
            if ($roommate->gender != $this->gender)                        // 排除男女混宿
            {
                $notes = "{'reason':'$roommate_name" . "与报名者为异性'}";
                $this->addEvent('roommate_auto_fail', $notes);
                return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;室友为异性";
            }
            $this->roommate_user_id = $roommate->id;       
            $this->save();
            $this->addEvent('roommate_auto_success', '');
            $roommate->roommate_user_id = $this->user->id;
            $roommate->save();
//            return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;成功";
        }
    }
    
    public function roommate() {
        return $this->belongsTo('App\Reg', 'roommate_reg_id'); 
    }
    
}
