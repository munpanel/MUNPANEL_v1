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
        if ($request->route()->action['domain'] == '{domain}')
        {
            // for portal pages not exist in specific route
            //To-Do configuration for domain
            if ($request->route()->domain == 'portal.munpanel.com')
            {
                return redirect('https://portal.munpanel.com/');
            }
            //To-Do: query for conference id and reject invalid domains
            config(['munpanel.conference_id' => $request->route()->domain]);
        }
        return $next($request);
    }
}
