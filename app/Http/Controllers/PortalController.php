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
            $query->whereExists(function ($query) {
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
        if (DB::table('school_user')
            ->whereUserId(Auth::id())
            ->whereSchoolId($team->id)
            ->count() > 0)
            $a=1;//return 'Already Member!';
        //$team->users()->attach(Auth::id());
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
}
