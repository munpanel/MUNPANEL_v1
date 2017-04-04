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

namespace App\Http\Middleware;

use Closure;
use App\User;
use App\Reg;
use App\Conference;
use Illuminate\Support\Facades\Route;

class CheckVerification
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
//        return $next($request);
        $user = $request->user();
        if (null !== $user) // if user is not logged in, the Auth middleware will do the job.
        {
            $route = $request->route()->uri;
            ///*
            if (substr($route, 0, 6) != 'verify') {
                if ($user->emailVerificationToken != 'success')
                    return redirect(mp_url('/verifyEmail'));
                if ($user->telVerifications != -1) //3/2/1: tries left; -1: activated
                    return redirect(mp_url('/verifyTel'));
            }
            //*/
            $cid = config('munpanel.conference_id');
            if (isset($cid)) //portal pages otherwise, will use Auth::user() instead
            {
                $regid = $request->session()->get('regIdforConference'.$cid);
                if (!isset($regid) || Reg::find($regid)->conference_id != $cid || Reg::find($regid)->user_id != $request->user()->id)
                {
                    // 仅寻找有效的报名
                    $regs = $user->regs()->where('conference_id', $cid)->where('enabled', true)->get();
                    if ($regs->count() == 0)
                    {
                        // 如果某参会者审核未通过，enabled 将设为 0，此人登录时应寻找审核未通过的 Reg （登进去之后会弹窗）
                        $regs1 = $user->regs()->where('conference_id', $cid)->get();
                        if ($regs->count() == 0)
                            $reg = Reg::create(['conference_id' => $cid, 'user_id' => $user->id, 'type' => 'unregistered', 'enabled' => true]);
                        else
                            $reg = $regs1[0];
                        $reg->login(true);
                    }
                    else if ($regs->count() == 1)
                        $regs[0]->login(true);
                    else
                        $regs[0]->login(false);
                }
            }

        }
        return $next($request);
    }
}
