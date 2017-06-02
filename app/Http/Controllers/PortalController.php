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
        $teams = School::withCount(['teamadmins' => function ($query) {
            $query->whereExists(function ($query) {
                $query->select(DB::raw(1))
                      ->from('regs')
                      ->whereRaw('regs.user_id = ' . Auth::id() . ' and regs.id=teamadmins.reg_id');
            });
        }])->get();
        foreach ($teams as $team)
        {
            $result->push([
                'details' => '<a href="ot/teamDetails.modal/'. $team->id .'" data-toggle="ajaxModal" id="'. $team->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
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

    public function createTeam(Request $request)
    {
        $team = new School;
        $team->name = $request->name;
        $team->type = $request->type;
        $team->description = $request->description;
        $team->joinCode = generateID(32);
        $team->save();
        $reg = new Reg;
        $reg->user_id = Auth::id();
        $reg->type = 'teamadmin';
        $reg->save();
        $teamadmin = new Teamadmin;
        $teamadmin->reg_id = $reg->id;
        $teamadmin->school_id = $team->id;
        $teamadmin->save();
        return redirect('/teams');
    }
}
