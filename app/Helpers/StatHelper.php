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
/**
 * phpdoc 以后再写
 *
 *
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
