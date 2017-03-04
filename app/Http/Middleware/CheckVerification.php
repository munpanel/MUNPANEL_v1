<?php

namespace App\Http\Middleware;

use Closure;
use App\User;
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
        if (null !== $request->user()) // if user is not logged in, the Auth middleware will do the job.
        {
            $route = $request->route()->uri;
            if (substr($route, 0, 6) != 'verify') {
                if ($request->user()->emailVerificationToken != 'success')
                    return redirect(secure_url('/verifyEmail'));
                if ($request->user()->telVerifications != -1) //3/2/1: tries left; -1: activated
                    return redirect(secure_url('/verifyTel'));
            }
        }
        return $next($request);
    }
}
