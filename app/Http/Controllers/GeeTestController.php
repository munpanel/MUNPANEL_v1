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

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Config;

class GeeTestController extends Controller
{
    public function startCaptcha(Request $request) {
        $GtSdk = new GeetestLib(Config::get("geetest.api_id"), Config::get("geetest.api_key"));

        $data = array(
            'id' => session()->getId()
        );

        $status = $GtSdk->pre_process($data, 1);
        session(['gt_server' => $status]);
        echo $GtSdk->get_response_str();   //
    }
}
