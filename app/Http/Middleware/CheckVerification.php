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
            /*
            if (substr($route, 0, 6) != 'verify') {
                if ($user->emailVerificationToken != 'success')
                    return redirect(secure_url('/verifyEmail'));
                if ($user->telVerifications != -1) //3/2/1: tries left; -1: activated
                    return redirect(secure_url('/verifyTel'));
            }
            */
            $cid = config('munpanel.conference_id');
            if (isset($cid)) //portal pages otherwise, will use Auth::user() instead
            {
                $regid = $request->session()->get('regIdforConference'.$cid);
                if (!isset($regid) || Reg::find($regid)->conference_id != $cid || Reg::find($regid)->user_id != $request->user()->id)
                {
                    $regs = $user->regs()->where('conference_id', $cid)->get();
                    if ($regs->count() == 0)
                    {
                        $reg = Reg::create(['conference_id' => $cid, 'user_id' => $user->id, 'type' => 'unregistered', 'enabled' => true]);
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
