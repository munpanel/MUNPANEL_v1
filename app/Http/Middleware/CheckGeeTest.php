<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
 */

namespace App\Http\Middleware;

use Closure;
use Config;
use App\Http\Controllers\GeetestLib;

class CheckGeeTest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        dd($request);
        $GtSdk = new GeetestLib(Config::get("geetest.api_id"), Config::get("geetest.api_key"));

        $data = array(
            'id' => session()->getId()
        );

        if ($request->session()->get('gt_server') == 1) {   //服务器正常
            $result = $GtSdk->success_validate($request->geetest_challenge, $request->geetest_validate, $request->geetest_seccode, $data);
            if ($result) {
                return $next($request);
            } else{
                return redirect()->back();
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($request->geetest_challenge,$request->geetest_validate,$request->geetest_seccode)) {
                return $next($request);
            }else{
                return redirect()->back();
            }
        }
    }
}
