<?php
/**
 * Copyright (C) MUNPANEL
 * This file is part of MUNPANEL System.
 *
 * Open-sourced under AGPL v3 License.
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
            if (substr($route, 0, 6) != 'verify' && $route != 'logout' && $request->getHost() != "static.munpanel.com") {
                if ($user->emailVerificationToken != 'success')
                    return redirect()->guest(route('verifyEmail'));
                if ($user->telVerifications != -1) //3/2/1: tries left; -1: activated
                    return redirect()->guest(route('verifyTel'));
            }
            //*/
            $cid = config('munpanel.conference_id');
            if (isset($cid)) //portal pages otherwise, will use Auth::user() instead
            {
                $regid = $request->session()->get('regIdforConference'.$cid);
                $reg = Reg::find($regid);
                if (!is_object($reg) || $reg->conference_id != $cid || $reg->user_id != $request->user()->id || !$reg->enabled)
                {
                    // 仅寻找有效的报名
                    $regs = $user->regs()->where('conference_id', $cid)->where('enabled', true)->get();
                    if ($regs->count() == 0)
                    {
                        // 如果某参会者审核未通过，enabled 将设为 0，此人登录时应寻找审核未通过的 Reg （登进去之后会弹窗）
                        $regs1 = $user->regs()->where('conference_id', $cid)->get();
                        if ($regs1->count() == 0)
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
                $regid = $request->session()->get('regIdforConference'.$cid.'sudo');
                Reg::flushCurrent($reg);

                if (isset($regid))
                {
                    $sudoreg = Reg::find($regid);
                    if (!(is_object($sudoreg) && $reg->can('sudo') && $sudoreg->conference_id == $cid && $sudoreg->enabled))
                        $request->session()->forget('regIdforConference'.$cid.'sudo');
                    else
                        Reg::flushCurrent($sudoreg);
                }
                $reg = Reg::current();
                if (!$reg->enabled)
                {
                    if (substr($route, 0, 8) != 'disabled' && substr($route, 0, 6) != 'logout' && $route != 'doSwitchIdentity/{reg}')
                        return redirect(mp_url('/disabled'));
                }
            }

        }
        return $next($request);
    }
}
