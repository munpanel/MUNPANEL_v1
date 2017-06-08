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
use Illuminate\Support\Facades\Cache;


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
                $domain = $_SERVER['HTTP_HOST'];
                $conference_id = Cache::tags('domains')->get($domain);
                if (!isset($conference_id))
                {
                    $conference_id = DB::table('domains')->where('domain', $domain)->value('conference_id');
                    if (isset($conference_id))
                    {
                        config(['munpanel.conference_id' => $conference_id]);
                        Cache::tags('domains')->put($domain, $conference_id, 1440); // Takes a day for removing a domain to work.
                    }
                    else
                    {
                        if (Auth::check())
                            return redirect(route('portal'));
                        else
                            return redirect(route('landing'));
                    }
                } else
                    config(['munpanel.conference_id' => $conference_id]);
            }
        }
        return $next($request);
    }
}
