<?php

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
            config(['munpanel.conference_id' => $_SERVER['HTTP_HOST']]);
        }
        return $next($request);
    }
}
