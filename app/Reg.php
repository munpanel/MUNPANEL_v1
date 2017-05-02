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

use Illuminate\Cache\TaggableStore;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Model;

class Reg extends Model
{
    protected $fillable = ['user_id','conference_id','school_id','type','enabled','gender','reginfo','accomodate','roommate_reg_id'];

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
        return Reg::find(Reg::currentID());
    }

    static public function currentID()
    {
        $sessionName = 'regIdforConference'.Reg::currentConferenceID();
        $sudo = session($sessionName.'sudo');
        if (isset($sudo))
            return $sudo;
        return session($sessionName);
    }

    static public function currentConference()
    {
        return Conference::find(config('munpanel.conference_id'));
    }

    static public function currentConferenceID()
    {
        return config('munpanel.conference_id');
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
        $sessionName = 'regIdforConference'.Reg::currentConferenceID().'sudo';
        session([$sessionName => $this->id]);
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


    public function make()
    {
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
                $delegate->status = 'sVerified';
                $delegate->save();
                break;
            case 'observer':
                $observer = Observer::find($this->id);
                $observer->conference_id = $this->conference_id;
                $observer->school_id = $this->school_id;
                $observer->committee_id = json_decode($this->reginfo)->conference->committee;
                // TODO: 如果团队报名，则为 reg
                $observer->status = 'sVerified';
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
                $volunteer->status = 'sVerified';
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

    //Big block of caching functionality.
    public function cachedRoles()
    {
        $userPrimaryKey = $this->primaryKey;
        $cacheKey = 'roles_for_user_'.$this->$userPrimaryKey;
        if(Cache::getStore() instanceof TaggableStore) {
            return Cache::tags('reg_role')->remember($cacheKey, Config::get('cache.ttl'), function () {
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
}
