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
use Yajra\Datatables\Datatables;
use App\School;

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
        $teams = School::all();
        foreach ($teams as $team)
        {
            $result->push([
                'details' => '<a href="ot/teamDetails.modal/'. $team->id .'" data-toggle="ajaxModal" id="'. $team->id .'" class="details-modal"><i class="fa fa-search-plus"></i></a>',
                'id' => $team->id,
                'type' => $team->typeText(),
                'name' => $team->name,
                'admin' => '否'
            ]);
        }
        return Datatables::of($result)->make(true);
    }
}
