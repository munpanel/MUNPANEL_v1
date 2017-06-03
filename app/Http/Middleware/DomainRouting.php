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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DomainRouting
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
        if (!isset($request->route()->action['domain']))
        {
            if (is_null(config('munpanel.conference_id'))) //we may route all domains to one conference for debugging and developing.
            {
                $conference_id = DB::table('domains')->where('domain', $_SERVER['HTTP_HOST'])->value('conference_id');
                if (isset($conference_id))
                    config(['munpanel.conference_id' => $conference_id]);
                else
                {
                    if (Auth::check())
                        return redirect(route('portal'));
                    else
                        return redirect(route('landing'));
                }
            }
        }
        return $next($request);
    }
}
