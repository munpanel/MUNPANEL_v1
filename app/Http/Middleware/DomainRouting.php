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
            //To-Do: query for conference id and redirect invalid domains to portal
            if (is_null(config('munpanel.conference_id'))) //we may route all domains to one conference for debugging and developing.
                config(['munpanel.conference_id' => $_SERVER['HTTP_HOST']]);
        }
        return $next($request);
    }
}
