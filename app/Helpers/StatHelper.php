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

use App\Delegate;
use App\Observer;
use App\Volunteer;
use App\Dais;
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
    $delOVerify = Delegate::where('conference_id', $cid)->count() - $delUnOVerify;
    $obsUnOVerify = Observer::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $obsOVerify = Observer::where('conference_id', $cid)->count() - $obsUnOVerify;
    $volUnOVerify = Volunteer::where('conference_id', $cid)->where('status', 'sVerified')->count();
    $volOVerify = Volunteer::where('conference_id', $cid)->count() - $volUnOVerify;
    $all = Reg::where('conference_id', $cid)->whereIn('type', ['delegate', 'observer', 'volunteer'])->count();
    return ['oVerified' => $delOVerify + $obsOVerify + $volOVerify, 'oUnverified' => $delUnOVerify + $obsUnOVerify + $volUnOVerify, 'all' => $all];
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
 * provide stat data for interview
 *
 * @param int $cid conference_id
 * @return array statistics used on page
 */
function interviewStat($cid, $rid = 0)
{
    $interviews = $unarranged = 0;
    if ($rid == -1)
    {
        $interviews = Interview::where('conference_id', $cid)->get();
        $interviewsc = $interviews->count();
        $unarranged = $interviews->where('status', 'assigned')->count();
        $unfinished = $interviews->where('status', 'arranged')->count();
        $exempted = $interviews->where('status', 'exempted')->count();
        $success = $interviews->where('status', 'passed')->count();
    }
    $arranged = $interviewsc - $unarranged;
    $finished = $arranged - $unfinished - $exempted;
    $roleSetable = $exempted + $success;
    return ['all' => $interviewsc, 'unarranged' => $unarranged, 'arranged' => $arranged, 'unfinished' => $unfinished, 'finished' => $finished, 'passed' => $roleSetable];
}
