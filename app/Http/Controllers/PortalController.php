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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\Datatables\Datatables;
use App\School;
use App\Conference;
use App\Reg;
use App\User;
use App\Teamadmin;

class PortalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('portal.home');
    }

    public function teams()
    {
        return view('portal.teams');
    }

    /**
     * Show teams datatables json
     *
     * @return string JSON of teams
     */
    public function teamsTable()
    {
        $result = new Collection;
        $teams = Auth::user()->schools()->withCount(['teamadmins' => function ($query) {
            $query->whereNull('conference_id')->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('regs')
                      ->whereRaw('regs.user_id = ' . Auth::id() . ' and regs.id=teamadmins.reg_id');
            });
        }])->get();
        foreach ($teams as $team)
        {
            $result->push([
                'details' => '<a href="teams/'. $team->id .'/details.modal" data-toggle="ajaxModal" id="'. $team->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $team->id,
                'type' => $team->typeText(),
                'name' => $team->name,
                'admin' => $team->teamadmins_count > 0 ? '是':'否'
            ]);
        }
        return Datatables::of($result)->make(true);
    }

    public function newTeamModal()
    {
        return view('portal.newTeam');
    }

    public function joinTeamModal()
    {
        return view('portal.joinTeam');
    }

    public function detailsModal($id)
    {
        $school = School::findOrFail($id);
        if (DB::table('school_user')
            ->whereUserId(Auth::id())
            ->whereSchoolId($id)
            ->count() > 0)
            return view('portal.teamDetailsModal', ['school' => $school, 'isAdmin' => $school->isAdmin()]);
        return 'error';
    }

    public function createTeam(Request $request)
    {
        $uid = Auth::id();
        $team = School::where('name', $request->name);
        if (!is_null($team) && $team->isAdmin())
            return 'team already exist';
        if (is_null($team))
            $team = new School;
        $team->name = $request->name;
        $team->type = $request->type;
        $team->description = $request->description;
        $team->joinCode = generateID(32);
        $team->save();
        $team->users()->attach($uid);
        $reg = new Reg;
        $reg->user_id = $uid;
        $reg->type = 'teamadmin';
        $reg->enabled = 1;
        $newreg->school_id = $team->id;
        $reg->save();
        $teamadmin = new Teamadmin;
        $teamadmin->reg_id = $reg->id;
        $teamadmin->school_id = $team->id;
        $teamadmin->save();
        return back();
    }

    public function joinTeam(Request $request)
    {
        $code = $request->code;
        $team = School::where('joinCode', $code)->first();
        if (isset($request->reg_id))
        {
            $reg = Reg::find($request->reg_id);
            if ($reg->user_id == Auth::id() && is_null($reg->school_id))
            {
                $reg->school_id = $team->id;
                $reg->save();
                if ($reg->specific()->status == 'sVerified')
                {
                    $specific = $reg->specific();
                    $specific->status = 'reg';
                    $specific->save();
                }
            }
        }
        if (DB::table('school_user')
            ->whereUserId(Auth::id())
            ->whereSchoolId($team->id)
            ->count() > 0)
            return 'Already Member!';
        $team->users()->attach(Auth::id());
        return back(); 
    }

    /**
     * Update a property of a team.
     *
     * @param Request $request
     * @param int $id the id of the team to be updated
     * @return void
     */
    public function updateTeam(Request $request, $id)
    {
        $school = School::findOrFail($id);
        if (!$school->isAdmin())
            return 'error';
        $name = $request->get('name');
        $value = $request->get('value');
        $school->$name = $value;
        $school->save();
    }

    public function teamAdmin($id)
    {
        $school = School::findOrFail($id);
        if (!$school->isAdmin())
            return 'error';
        return view('portal.teamAdmin', ['team' => $school]);
    }

    public function teamMembers($id)
    {
        $school = School::findOrFail($id);
        if (!$school->isAdmin())
            return 'error';
        return view('portal.teamMembers', ['team' => $school]);
    }

    /**
     * Show team member datatables json
     *
     * @return string JSON of team members
     */
    public function groupMemberTable($id)
    {
        $user = Auth::user();
        $school = School::findOrFail($id);
        if ($school->isAdmin())
        {
            $result = new Collection;
            $users = User::with(['regs.teamadmin' => function($query) use($school) {
                $query->whereRaw('teamadmins.school_id = ' . $school->id);
            }])->whereExists( function($query) use($school, $user) {
                $query->select(DB::raw(1))
                      ->from('school_user')
                      ->whereRaw('school_user.user_id = users.id and school_user.school_id=' . $school->id);
            })->get(['id', 'email', 'name', 'tel']);
            foreach ($users as $user)
            {
                $globalAdmin = false;
                $confAdmins = 0;
                // TODO: ignore conference with status in ['finished', 'cancelled']
                foreach ($user->regs as $reg)
                {
                    if (is_object($reg->teamadmin))
                    {
                        if ($reg->conference_id == 0)
                        {
                           $globalAdmin = true;
                           break;
                        } else
                            $confAdmins++;
                    }
                }
                $adminText = '否';
                if ($globalAdmin)
                    $adminText = '全局';
                elseif ($confAdmins > 0)
                    $adminText = $confAdmins.'场会议';
                $result->push([
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'tel' => $user->tel,
                    'admin' => '<a href="teams/'.$id.'/groupMember/'.$user->id.'/admin.modal" data-toggle="ajaxModal">'.$adminText.'</a>',
                ]);
            }
            return Datatables::of($result)->make(true);
        }
    }

    public function groupMemberAdminModal($gid, $uid)
    {
        //TODO: auth check
        $user = User::findOrFail($gid);
        $conferences = Conference::whereIn('status', ['prep', 'reg']);
        return view('portal.groupMemberAdminModal', ['user' => $user, 'confs' => $conferences, 'group' => $gid]);
    }

    public function addAdmin(Request $request)
    {
        $user = User::findOrFail($request->user);
        $conference_id = Cache::tags('domains')->get($domain);
        if (!isset($conference_id))
            $conference_id = DB::table('domains')->where('domain', $domain)->value('conference_id');
        if (!isset($conference_id))
            return '会议不存在！';
        $conference = Conference::findOrFail($conference_id);
        if (!in_array($conference->status, ['prep', 'reg']))
            return '会议未开放报名，不能注册领队！';
        $group = $request->gid;
        // 假设单场会议单个团队只能有一个 teamadmin
        $admins = Reg::where('conference_id', $conference->id)->where('school_id', $gid)->where('type', 'teamadmin')->count();
        if ($admins > 0)
            return '本团队在该会议已注册领队，不能重复注册！';
        $new = new Reg;
        $new->user_id = $request->user;
        $new->conference_id = $conference_id;
        $new->school_id = $gid;
        $new->type = 'teamadmin';
        $new->save();
        return 'success';
    }
}
