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

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class Reg extends Model
{
    protected $fillable = ['user_id','conference_id','school_id','type','enabled','gender','reginfo','accomodate','roommate_reg_id'];
    private static $_current, $_currentConference;

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

    public function teamadmin() {
        return $this->hasOne('App\Teamadmin');
    }

    public function cards() {
        return $this->hasMany('App\Card', 'reg_id');
    }

    public function events() {
        return $this->hasMany('App\Event');
    }

    public function handins() {
        return $this->hasMany('App\Handin');
    }

    public function notes() {
        return $this->hasMany('App\Note');
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

     public function interviewer() {
        return $this->hasOne('App\Interviewer');
    }

    public function roommate() {
        return $this->belongsTo('App\User', 'roommate_reg_id');
    }

    public function interviews()
    {
        return $this->hasMany('App\Interview');
    }

    public function name()
    {
        return $this->user->name;
    }

    public function assignCommittees()
    {
        return $this->belongstoMany('App\Committee', 'seatassigners', 'reg_id', 'committee_id');
    }

    public function regText() {
        if (is_object($this->specific()))
            return $this->specific()->regText();
        return '访客';
    }

    public function committee() {
        return $this->specific()->belongsTo('App\Committee');
    }

    static public function current()
    {
        if (!is_null(self::$_current))
            return self::$_current;
        self::$_current = Reg::find(Reg::currentID());
        return self::$_current;
        return Reg::find(Reg::currentID());
    }

    static public function flushCurrent($current = null)
    {
        self::$_current = $current;
    }

    static public function currentID()
    {
        $sessionName = 'regIdforConference'.Reg::currentConferenceID();
        $sudo = session($sessionName.'sudo');
        if (isset($sudo))
            return $sudo;
        return session($sessionName);
    }

    static public function currentUserID()
    {
        $reg = Reg::current();
        if (is_object($reg))
            return $reg->user_id;
        return Auth::id();
    }

    static public function currentUser()
    {
        $reg = Reg::current();
        if (is_object($reg))
            return $reg->user;
        return Auth::user();
    }

    static public function currentConference()
    {
        if (!is_null(self::$_currentConference))
            return self::$_currentConference;
        self::$_currentConference = Conference::find(Reg::currentConferenceID());
        self::$_currentConference->load('options');
        return self::$_currentConference;
        return Conference::find(Reg::currentConferenceID());
    }

    static public function currentConferenceID()
    {
        return config('munpanel.conference_id') ?? 0;
    }

    static public function selectConfirmed()
    {
        return session('regIdforConference'.Reg::currentConferenceID().'confirm');
    }

    public function login($confirm)
    {
        $sessionName = 'regIdforConference'.Reg::currentConferenceID();
        session([$sessionName => $this->id]);
        session([$sessionName.'confirm' => $confirm]);
        session()->forget($sessionName.'sudo');
    }

    public function sudo()
    {
        $sessionName = 'regIdforConference'.Reg::currentConferenceID();
        session([$sessionName.'sudo' => $this->id]);
        session([$sessionName.'confirm' => true]);
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

    public function currentInterview()
    {
        $interview = $this->interviews()->orderBy('created_at', 'dsc')->first();
        if (is_object($interview))
        {
            if (!in_array($interview->status, ['assigned', 'arranged', 'undecided']))
                $interview = null;;
        }
        return $interview;
    }

    public function currentInterviewID()
    {
        $interview = $this->currentInterview();
        if (is_object($interview))
            return $interview->id;
        return null;
    }

    public function statusText()
    {
        return $this->specific()->statusText();
    }

    public function make()
    {
        $status = 'reg';
        $group = json_decode($this->reginfo)->conference->groupOption;
        if ($group == 'personal'||Reg::currentConferenceID() == 2) $status = 'sVerified'; // TODO: change conferenceID temporary patch
        switch ($this->type)
        {
            case 'delegate':
                $delegate = Delegate::find($this->id);
                if (is_null($delegate))
                    $delegate = new Delegate();
                $delegate->reg_id = $this->id;
                $delegate->conference_id = $this->conference_id;
                $delegate->school_id = $this->school_id;
                $delegate->committee_id = json_decode($this->reginfo)->conference->committee;
                // TODO: 如果团队报名，则为 reg
                $delegate->status = $status;
                $delegate->save();
                break;
            case 'observer':
                $observer = Observer::find($this->id);
                $observer->conference_id = $this->conference_id;
                $observer->school_id = $this->school_id;
                $observer->committee_id = json_decode($this->reginfo)->conference->committee;
                // TODO: 如果团队报名，则为 reg
                $observer->status = $status;
                $observer->save();
                break;
            case 'volunteer':
                $volunteer = Volunteer::find($this->id);
                if (is_null($volunteer))
                    $volunteer = new Volunteer();
                $volunteer->reg_id = $this->id;
                $volunteer->conference_id = $this->conference_id;
                $volunteer->school_id = $this->school_id;
                // TODO: 如果团队报名，则为 reg
                $volunteer->status = $status;
                $volunteer->save();
                break;
            case 'dais':
                $dais = Dais::find($this->id);
                if (is_null($dais))
                    $dais = new Dais();
                $dais->reg_id = $this->id;
                $dais->conference_id = $this->conference_id;
                $dais->school_id = $this->school_id;
                //$dais->status = $dais->nextStatus();
                $dais->handin = '{"language":"'.json_decode($this->reginfo)->conference->language.'"}';
                $dais->save();
                break;
            case 'ot':
                $ot = Orgteam::find($this->id);
                if (is_null($ot))
                    $ot = new Orgteam();
                $ot->reg_id = $this->id;
                $ot->conference_id = $this->conference_id;
                $ot->school_id = $this->school_id;
                $ot->handin = '{"form":"'.json_decode(Reg::currentConference()->option('reg_tables'))->otregForm.'"}';
                //$ot->status = $ot->nextStatus();
                $ot->save();
                break;
        }
    }

    public function assignRoommateByName($option = null)
    {
        if ($option == null)
            $option = json_decode('{"one_empty":"autofill","mf_roommate":"false"}');
        $myname = $this->user->name;
        if (!$this->accomodate) return "$myname &#09;0000&#09;未申请住宿";
        $this->roommate_user_id = null;
        $roommate_name = $this->getInfo('conference.roommatename');
        if (!empty($roommate_name))
        {
            // 对于带空格的roommatename值，在此if表达式外增加foreach表达式以逐一处理
            $roommates = User::where('name', $roommate_name)->get()->pluck(['id']);
            $roommates_reg = Reg::whereIn('user_id', $roommates)->where('conference_id', Reg::currentConferenceID())->whereIn('type', ['ot', 'dais', 'delegate', 'observer', 'volunteer'])->get();
            $count = $roommates_reg->count();
            if ($count == 0)
            {
                $notes = "{\"reason\":\"未找到室友$roommate_name" . "的报名记录\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname &#09;0000&#09;室友姓名$roommate_name&#09;未找到室友的报名记录";
            }
            $roommate = $roommates_reg->first();
            if ($count > 1)
            {
                foreach ($roommates_reg as $roommate1)
                {
                    if ($roommate1->type == 'unregistered') continue;                    // 排除未注册室友
                    $roommate = $roommate1;
                    break;
                }
            }
            if ($roommate->user_id == $this->user->id)                               // 排除自我配对
            {
                $notes = "{\"reason\":\"$myname" . "申报的室友与报名者本人重合\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname &#09;$roommate->id &#09;自我配对";
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
            if (!in_array($typedroommate->status, ['unpaid', 'paid', 'oVerified']))   // 排除未通过审核室友
            {
                $notes = "{\"reason\":\"室友$roommate_name" . "的报名仍未通过审核\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname&#09;$roommate->id &#09;室友姓名$roommate_name&#09;未通过审核";
            }
            if (!$roommate->accomodate)                                    // 排除对方未申请住宿
            {
                $notes = "{\"reason\":\"$roommate_name" . "未申请住宿\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname &#09;$roommate->id &#09;室友姓名$roommate_name&#09;未申请住宿";
            }
            $typedroommate_name = $roommate->getInfo('conference.roommatename');
            if (empty($typedroommate_name))                          // 如果对方未填室友，自动补全
            {
                if ($option->one_empty == 'autofill')
                {
                    $typedroommate_name = $myname;
                    $roommate->updateInfo('conference.roommatename', $myname);
                }
                else
                {
                    $notes = "{\"reason\":\"$roommate_name" . "未填写室友姓名\"}";
                    $this->addEvent('roommate_auto_fail', $notes);
                    return "$myname &#09;$roommate->id &#09;室友姓名$roommate_name&#09;对方未填写室友姓名";
                }
            }
            if ($typedroommate_name != $myname) //continue;}           // 排除多角室友
            {
                $notes = "{\"reason\":\"$roommate_name" . "申报的室友并非$myname" . "本人\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname &#09;$roommate->id &#09;室友姓名$roommate_name&#09;多角室友";
            }
            if (($roommate->gender != $this->gender) && !$option->mf_roommate)                        // 排除男女混宿
            {
                $notes = "{\"reason\":\"$roommate_name" . "与报名者为异性\"}";
                $this->addEvent('roommate_auto_fail', $notes);
                return "$myname &#09;$roommate->id &#09;室友姓名$roommate_name&#09;室友为异性";
            }
            $this->roommate_user_id = $roommate->user_id;
            $this->save();
            $this->addEvent('roommate_auto_success', '');
            $roommate->roommate_user_id = $this->user->id;
            $roommate->save();
            return $myname  ."&#09;".$roommate->id . "&#09;室友姓名$roommate_name&#09;成功";
        }
    }

    public function assignRoommateByRid($rid, $admin = false)
    {
        $reg = Reg::findOrFail($rid);
        if (!empty($reg->roommate_user_id))
            return "目标已有室友分配！";
        $this->roommate_user_id = $reg->user->id;
        $name = $reg->user->name;
        $this->save();
        if ($admin == true)
            $this->addEvent('roommate_submitted', '{"name":"'.Auth::user()->name."\",\"roommate\":\"$name\"}");
        else
            $this->addEvent('roommate_manual_success', '');
        $reg->roommate_user_id = $this->user->id;
        $name = $this->user->name;
        $reg->save();
        if ($admin == true)
            $reg->addEvent('roommate_submitted', '{"name":"'.Auth::user()->name."\",\"roommate\":\"$name\"}");
        else
            $reg->addEvent('roommate_manual_success', '');
        return "success";
    }

    public function assignRoommateByCode($id)
    {
        if ($this->confernece->option('roommate_paired') != 2)
            return "当前不允许执行配对操作！"
        $rid = DB::table('linking_codes')->where('id', $id)->where('type', 'roommate')->pluck('reg_id');
        if ($rid->count() == 0)
            return "配对码错误！";
        $reg = Reg::findOrFail($rid[0]);
        if ($reg->roommate_user_id == $this->user->id)
            return "您已与目标配对！";
        if (!empty($reg->roommate_user_id) || !empty($this->roommate_user_id))
        {
            $roommates_reg = Reg::whereIn('user_id', [$reg->roommate_user_id, $this->roommate_user_id])->where('conference_id', Reg::currentConferenceID())->whereIn('type', ['ot', 'dais', 'delegate', 'observer', 'volunteer'])->whereNotNull('roommate_user_id')->get();
            foreach ($roommates_reg as $roommate)
            {
                $roommate->roommate_user_id = null;
                $roommate->save();
            }
            $reg->roommate_user_id = null;
            $reg->save();
            $this->roommate_user_id = null;
            $this->save();
        }
        $result = $reg->assignRoommateByRid($this->id);
        if ($result == 'success')
            DB::table('linking_codes')->where('id', $id)->where('type', 'roommate')->delete();
        return $result;
    }

    public function generateLinkCode($roommate = true, $partner = true)
    {
        $code = generateID(8);
        if ($this->accomodate && $roommate)
        {
            if (DB::table('linking_codes')->where('type', 'roommate')->where('reg_id', $this->id)->count() > 0)
                DB::table('linking_codes')->where('type', 'roommate')->where('reg_id', $this->id)->delete();
            DB::table('linking_codes')->insert(['id' => $code, 'type' => 'roommate', 'reg_id' => $this->id]);
        }
        if (isset($this->delegate) && $this->delegate->committee->is_dual && $partner)
        {
            if (DB::table('linking_codes')->where('type', 'partner')->where('reg_id', $this->id)->count() > 0)
                DB::table('linking_codes')->where('type', 'partner')->where('reg_id', $this->id)->delete();
            DB::table('linking_codes')->insert(['id' => $code, 'type' => 'partner', 'reg_id' => $this->id]);
        }
    }

    //Big block of caching functionality.
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'roles_for_user_'.$this->$userPrimaryKey;
        if(Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('reg_role')->remember($cacheKey, Config::get('cache.ttl', 60), function () {
                return $this->roles()->get();
            });
        }
        else return $this->roles()->get();
    }
    public function save(array $options = [])
    {   //both inserts and updates
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags('reg_role')->flush();
        }
        return parent::save($options);
    }
    public function delete(array $options = [])
    {   //soft or hard
        parent::delete($options);
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags('reg_role')->flush();
        }
    }
    public function restore()
    {   //soft delete undo's
        parent::restore();
        if(Cache::getStore() instanceof TaggableStore) {
            Cache::tags('reg_role')->flush();
        }
    }

    /**
     * Many-to-Many relations with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    /**
     * Boot the user model
     * Attach event listener to remove the many-to-many records when trying to delete
     * Will NOT delete any records if the user model uses soft deletes.
     *
     * @return void|bool
     */
    public static function boot()
    {
        parent::boot();

        static::deleting(function($user) {
            if (!method_exists('App\Reg', 'bootSoftDeletes')) {
                $user->roles()->sync([]);
            }

            return true;
        });
    }

    /**
     * Checks if the user has a role by its name.
     *
     * @param string|array $name       Role name or array of role names.
     * @param bool         $requireAll All roles in the array are required.
     *
     * @return bool
     */
    public function hasRole($name, $requireAll = false)
    {
        if (is_array($name)) {
            foreach ($name as $roleName) {
                $hasRole = $this->hasRole($roleName);

                if ($hasRole && !$requireAll) {
                    return true;
                } elseif (!$hasRole && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the roles were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the roles were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                if ($role->name == $name) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user has a permission by its name.
     *
     * @param string|array $permission Permission string or array of permissions.
     * @param bool         $requireAll All permissions in the array are required.
     *
     * @return bool
     */
    public function can($permission, $requireAll = false)
    {
        if (is_array($permission)) {
            foreach ($permission as $permName) {
                $hasPerm = $this->can($permName);

                if ($hasPerm && !$requireAll) {
                    return true;
                } elseif (!$hasPerm && $requireAll) {
                    return false;
                }
            }

            // If we've made it this far and $requireAll is FALSE, then NONE of the perms were found
            // If we've made it this far and $requireAll is TRUE, then ALL of the perms were found.
            // Return the value of $requireAll;
            return $requireAll;
        } else {
            foreach ($this->cachedRoles() as $role) {
                // Validate against the Permission table
                foreach ($role->cachedPermissions() as $perm) {
                    if (str_is( $permission, $perm->name) ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Checks role(s) and permission(s).
     *
     * @param string|array $roles       Array of roles or comma separated string
     * @param string|array $permissions Array of permissions or comma separated string.
     * @param array        $options     validate_all (true|false) or return_type (boolean|array|both)
     *
     * @throws \InvalidArgumentException
     *
     * @return array|bool
     */
    public function ability($roles, $permissions, $options = [])
    {
        // Convert string to array if that's what is passed in.
        if (!is_array($roles)) {
            $roles = explode(',', $roles);
        }
        if (!is_array($permissions)) {
            $permissions = explode(',', $permissions);
        }

        // Set up default values and validate options.
        if (!isset($options['validate_all'])) {
            $options['validate_all'] = false;
        } else {
            if ($options['validate_all'] !== true && $options['validate_all'] !== false) {
                throw new InvalidArgumentException();
            }
        }
        if (!isset($options['return_type'])) {
            $options['return_type'] = 'boolean';
        } else {
            if ($options['return_type'] != 'boolean' &&
                $options['return_type'] != 'array' &&
                $options['return_type'] != 'both') {
                throw new InvalidArgumentException();
            }
        }

        // Loop through roles and permissions and check each.
        $checkedRoles = [];
        $checkedPermissions = [];
        foreach ($roles as $role) {
            $checkedRoles[$role] = $this->hasRole($role);
        }
        foreach ($permissions as $permission) {
            $checkedPermissions[$permission] = $this->can($permission);
        }

        // If validate all and there is a false in either
        // Check that if validate all, then there should not be any false.
        // Check that if not validate all, there must be at least one true.
        if(($options['validate_all'] && !(in_array(false,$checkedRoles) || in_array(false,$checkedPermissions))) ||
            (!$options['validate_all'] && (in_array(true,$checkedRoles) || in_array(true,$checkedPermissions)))) {
            $validateAll = true;
        } else {
            $validateAll = false;
        }

        // Return based on option
        if ($options['return_type'] == 'boolean') {
            return $validateAll;
        } elseif ($options['return_type'] == 'array') {
            return ['roles' => $checkedRoles, 'permissions' => $checkedPermissions];
        } else {
            return [$validateAll, ['roles' => $checkedRoles, 'permissions' => $checkedPermissions]];
        }

    }


    /**
     * Alias to eloquent many-to-many relation's attach() method.
     *
     * @param mixed $role
     */
    public function attachRole($role)
    {
        if(is_object($role)) {
            $role = $role->getKey();
        }

        if(is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->attach($role);
    }

    /**
     * Alias to eloquent many-to-many relation's detach() method.
     *
     * @param mixed $role
     */
    public function detachRole($role)
    {
        if (is_object($role)) {
            $role = $role->getKey();
        }

        if (is_array($role)) {
            $role = $role['id'];
        }

        $this->roles()->detach($role);
    }

    /**
     * Attach multiple roles to a user
     *
     * @param mixed $roles
     */
    public function attachRoles($roles)
    {
        foreach ($roles as $role) {
            $this->attachRole($role);
        }
    }

    /**
     * Detach multiple roles from a user
     *
     * @param mixed $roles
     */
    public function detachRoles($roles=null)
    {
        if (!$roles) $roles = $this->roles()->get();

        foreach ($roles as $role) {
            $this->detachRole($role);
        }
    }

    public function updateInfo($name, $value)
    {
        $keys = explode('.', $name);
        if ($keys[0] == 'reg')
            $this->{$keys[1]} = $value;
        else
        {
            $regInfo = json_decode($this->reginfo);
            $regInfo->{$keys[0]}->{$keys[1]} = $value;
            $this->reginfo = json_encode($regInfo);
        }
    }

    public function getInfo($name)
    {
        $keys = explode('.', $name);
        if ($keys[0] == 'reg')
            return $this->{$keys[1]};
        else
        {
            $regInfo = json_decode($this->reginfo);
            if (is_object($regInfo) && is_object($regInfo->{$keys[0]}) && isset($regInfo->{$keys[0]}->{$keys[1]}))
                return $regInfo->{$keys[0]}->{$keys[1]};
            else
                return null;
        }
    }

    public function schoolName()
    {
        $school = $this->school;
        if (is_object($school))
            return '(团队) '.$school->name;
        $school = $this->getInfo('personinfo.school'); 
        if (isset($school))
            return '(个人) '.$school;
        return '无';
    }

    public function createConfOrder()
    {
        $conf = $this->conference;
        $order_details = json_decode($conf->option('reg_order'), true);
        $i = 0;
        $orderContent = array();
        $price = 0;
        foreach ($order_details as $item_details)
        {
            $criteria = $item_details['criteria'];
            $skip = false;
            if (is_array($criteria))
            {
                foreach($criteria as $criterion)
                {
                    if ($this->getInfo($criterion['name']) != $criterion['value'])
                    {
                        $skip = true;
                        break;
                    }
                }
            }
            if ($skip)
                continue;
            $rowId = 'confOrder'. $i++;
            $orderItem = array();
            $orderItem['rowId'] = $rowId;
            $orderItem['id'] = 'NID_'.$item_details['id'];
            $orderItem['name'] = $item_details['name'];
            $orderItem['qty'] = $item_details['qty'];
            $orderItem['price'] = $item_details['price'];
            $orderItem['options'] = array();
            $orderItem['tax'] = 0;
            $orderItem['subtotal'] = $item_details['price'] * $item_details['qty'];
            $price += $orderItem['subtotal'];
            $orderContent[$rowId] = $orderItem;
        }
        if (count($orderContent) == 0)
        {
            $specific = $this->specific();
            if (is_object($specific))
            {
                $specific->status = 'paid';
                $specific->save();
            }
            return;
        }
        $order = new Order;
        $order->id = date("YmdHis").generateID(6);
        $order->user_id = $this->user_id;
        $order->conference_id = $conf->id;
        $order->content = json_encode($orderContent);
        $order->price = $price;
        $order->shipment_method = 'none';
        $order->save();
        $this->order_id = $order->id;
        $this->save();
        $this->user->sendSMS('感谢您报名'.$conf->name.'，系统已为您创建您的报名费用订单（ID: '.$order->id.'），共计'.number_format($order->price, 2).'元，烦请您尽快访问 https://portal.munpanel.com/store/orders 付款。您可通过系统中的二维码通过 MUNPANEL Pay (beta) 支付宝线上缴费，付款完成自动确认缴费状态；您亦可使用会议指定的其他缴费方式并等待手动确认。感谢您的支持与配合，祝您开会愉快。');
    }
}
