<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

use App\Delegate;
use App\Observer;
use App\Volunteer;
use App\Dais;
use App\Orgteam;
use App\Interview;

/**
 * provide stat data for reg dashboard (算法似乎不一定准确，到时候还要看情况调整)
 *
 * @param int $cid conference_id
 * @return array statistics used on page
 */
function oVerifyStat($cid)
{
    $delUnOVerify = Delegate::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $delOVerify = Delegate::where('conference_id', $cid)->where('status', '!=', 'reg')->count() - $delUnOVerify;
    $obsUnOVerify = Observer::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $obsOVerify = Observer::where('conference_id', $cid)->where('status', '!=', 'reg')->count() - $obsUnOVerify;
    $volUnOVerify = Volunteer::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $volOVerify = Volunteer::where('conference_id', $cid)->where('status', '!=', 'reg')->count() - $volUnOVerify;
    $all = Reg::where('conference_id', $cid)->whereIn('type', ['delegate', 'observer', 'volunteer'])->count();
    $interviewQuery = DB::select('SELECT count(DISTINCT reg_id) as intv FROM `interviews` where `conference_id` = ? and `status` not in (\'cancelled\', \'failed\');', [$cid]);
    $interviews = $interviewQuery[0]->intv;
    $roleSelQuery = DB::select('SELECT count(*) as qty FROM `delegate_info` WHERE `conference_id` = ? AND `nation_id` IS NOT NULL', [$cid]);
    $roleSel = $roleSelQuery[0]->qty;
    return ['oVerified' => $delOVerify + $obsOVerify + $volOVerify, 
            'oUnverified' => $delUnOVerify + $obsUnOVerify + $volUnOVerify, 
            'sVerified' => $delOVerify + $obsOVerify + $volOVerify + $delUnOVerify + $obsUnOVerify + $volUnOVerify, 
            'all' => $all, 
            'delOVerify' => $delOVerify, 
            'interviews' => $interviews,
            'roleSel' => $roleSel];
}

/**
 * provide stat data for daisreg dashboard
 *
 * @param int $cid conference_id
 * @return array statistics used on page
 */
function daisregStat($cid)
{
    $daisAll = Dais::where('conference_id', $cid)->count();
    $daisUnOVerify = Dais::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $daisSuccess = Dais::where('conference_id', $cid)->where('status', 'success')->count();
    $daisOVerify = Dais::where('conference_id', $cid)->whereIn('status', ['success', 'fail', 'oVerified'])->count();
    return ['oVerified' => $daisOVerify, 'oUnverified' => $daisUnOVerify, 'all' => $daisAll, 'success' => $daisSuccess];
}

/**
 * provide stat data for otreg dashboard
 *
 * @param int $cid conference_id
 * @return array statistics used on page
 */
function otregStat($cid)
{
    $otAll = Orgteam::where('conference_id', $cid)->count();
    $otUnOVerify = Orgteam::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $otSuccess = Orgteam::where('conference_id', $cid)->where('status', 'success')->count();
    $otOVerify = Orgteam::where('conference_id', $cid)->whereIn('status', ['success', 'fail', 'oVerified'])->count();
    return ['oVerified' => $otOVerify, 'oUnverified' => $otUnOVerify, 'all' => $otAll, 'success' => $otSuccess];
}

/**
 * provide stat data for interview
 *
 * @param int $cid conference_id
 * @return array statistics used on page
 */
function interviewStat($cid, $rid = 0)
{
    $interviewsc = $unarranged = $unfinished = $exempted = $cancelled = $success = 0;
    if ($rid == -1)
    {
        $interviews = Interview::where('conference_id', $cid)->get(['id', 'status']);
        $interviewsc = $interviews->groupBy('reg_id')->count();
        $unarranged = $interviews->where('status', 'assigned')->groupBy('reg_id')->count();
        $unfinished = $interviews->where('status', 'arranged')->groupBy('reg_id')->count();
        $exempted = $interviews->where('status', 'exempted')->groupBy('reg_id')->count();
        $cancelled = $interviews->where('status', 'cancelled')->groupBy('reg_id')->count();
        $success = $interviews->where('status', 'passed')->groupBy('reg_id')->count();
    }
    else
    {
        if ($rid == 0) $rid = Reg::currentID();
        $interviews = Interview::where('conference_id', $cid)->where('interviewer_id', $rid)->get(['id', 'status']);
        $interviewsc = $interviews->count();
        $unarranged = $interviews->where('status', 'assigned')->count();
        $unfinished = $interviews->where('status', 'arranged')->count();
        $exempted = $interviews->where('status', 'exempted')->count();
        $cancelled = $interviews->where('status', 'cancelled')->count();
        $success = $interviews->where('status', 'passed')->count();
    }
    $arranged = $interviewsc - $unarranged;
    $finished = $arranged - $unfinished;
    $roleSetable = $exempted + $success;
    return ['iid' => $rid, 'all' => $interviewsc, 'cancelled' => $cancelled, 'unarranged' => $unarranged, 'arranged' => $arranged, 'unfinished' => $unfinished, 'finished' => $finished, 'passed' => $roleSetable];
}
