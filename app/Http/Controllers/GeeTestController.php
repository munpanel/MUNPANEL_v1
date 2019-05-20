<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
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
