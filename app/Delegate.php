<?php

namespace App;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Delegate extends Model
{
    protected $table='delegate_info';
    protected $primaryKey = 'user_id';
    protected $fillable = ['user_id','school_id','status','gender','sfz','grade','email','qq','wechat','partnername','parenttel','tel','committee_id','accomodate','roommatename','partner_user_id','roommate_user_id','notes'];

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
        // TODO: 如果委员会为单带，return
        $this->partner_user_id = null;
        if (isset($this->partnername))
        {
            $partner_name = $this->partnername;
            $myname = $this->user->name;
            // TODO: 对于带空格的partnername值，在此if表达式外增加foreach表达式以逐一处理
            $partners = User::where('name', $partner_name);
            $count = $partners->count();
            if ($count == 0) 
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "未找到搭档$partner_name" . "的报名记录！";
                $this->save();
                return $myname . "&#09;0&#09;搭档姓名$partner_name&#09;未找到搭档的报名记录";
            }
            $partner = $partners->first();
            if ($count > 1)
            {
                foreach ($partners as $partner1)
                {
                    if ($partner1->type != 'delegate') continue;                        // 排除非代表搭档
                    if ($partner1->delegate->committee != $this->committee) continue;   // 排除非本委员会搭档
                    $partner = $partner1;
                    break;
                }
            }
            if ($partner->type != 'delegate') //continue;                        // 排除非代表搭档
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$partner_name" . "并未以代表身份报名！";
                $this->save();
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;不是代表";                
            }
            $delpartner = $partner->delegate;
            if ($delpartner->committee != $this->committee) //continue;          // 排除非本委员会搭档
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$partner_name" . "与$myname" . "并非同一委员会！";
                $this->save();
                return $myname  ."&#09;".$partner->id ."&#09;搭档姓名$partner_name&#09;不同委员会";
            }
            if (is_null($delpartner->partnername))                               // 如果对方未填搭档，自动补全
                $delpartner->partnername = $myname;
            if ($delpartner->partnername != $myname) //continue;                 // 排除多角搭档
            {
                if (isset($this->notes)) $this->notes .= "\n";
                $this->notes .= "$partner_name" . "申报的搭档并非$myname" . "本人！";
                $this->save();
                return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;多角搭档";                
            }
            $this->partner_user_id = $partner->id;
            $this->save();
            $delpartner->partner_user_id = $this->user->id;
            $delpartner->save();
            return $myname  ."&#09;".$partner->id . "&#09;搭档姓名$partner_name&#09;成功";
        }
        return $this->user->name . "&#09;未填写搭档姓名";
    }
    
    public function partner() {
        return $this->belongsTo('App\User', 'partner_user_id'); 
    }
    
    public function assignRoommateByName() 
    {
        if (!$this->accomodate) return $this->user->name . "&#09;0&#09;未申请住宿";
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
                $this->notes .= "未找到室友$roommate_name" . "的报名记录！";
                $this->save();
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
        return $this->belongsTo('App\User', 'roommate_user_id'); 
    }
    
    public function documents() {
        $result = new Collection;
        if (isset($this->nation))
        {
            $nationgroups = $this->nationgroups;
            if (isset($nationgroups))
            {
                foreach($nationgroups as $nationgroup)
                {
                    $documents = $nationgroup->documents;
                    if (isset($documents))
                        foreach ($documents as $document)
                            $result->push($document);
                }
            }
        }
        $documents = $this->committee->documents;
        if (isset($documents))
        {
            foreach ($documents as $document)
                $result->push($document);
        }
        $delegategroups = $this->delegategroups;
        if (isset($delegategroups))
        {
            foreach($delegategroups as $delegategroup)
            {
                $documents = $delegategroup->documents;
                if (isset($documents))
                    foreach ($documents as $document)
                        $result->push($document);
            }
        }
        return $result->unique()->sortBy('id');
    }
}
